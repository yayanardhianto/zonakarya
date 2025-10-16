<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestQuestion;
use App\Models\TestPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Barryvdh\DomPDF\Facade\Pdf;

class TestQuestionController extends Controller
{
    public function index(Request $request)
    {
        $packageId = $request->get('package_id');
        
        $query = TestQuestion::with(['packages', 'options']);
        
        // If package_id is provided, order questions so that those in the package appear first
        if ($packageId) {
            $query->orderByRaw("CASE WHEN EXISTS (
                SELECT 1 FROM test_package_question 
                WHERE test_package_question.test_question_id = test_questions.id 
                AND test_package_question.test_package_id = ?
            ) THEN 0 ELSE 1 END", [$packageId])
            ->orderBy('order');
        } else {
            $query->orderBy('order');
        }
        
        $questions = $query->paginate(10)->withQueryString();
        // $questions = $query->orderBy('order')->paginate(10);
        $packages = TestPackage::active()->get();
        
        return view('admin.test-question.index', compact('questions', 'packages', 'packageId'));
    }

    public function create(Request $request)
    {
        $packages = TestPackage::active()->get();
        $packageId = $request->get('package_id');
        
        return view('admin.test-question.create', compact('packages', 'packageId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'question_type' => 'required|in:multiple_choice,essay,scale,video_record,forced_choice',
            'points' => 'required|integer|min:1',
            'order' => 'required|integer|min:1',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*.option_text' => 'required_if:question_type,multiple_choice|string',
            'options.*.is_correct' => 'required_if:question_type,multiple_choice|boolean',
            'options.*.order' => 'required_if:question_type,multiple_choice|integer|min:0'
            // 'traits' => 'required_if:question_type,forced_choice|string'
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('question_image')) {
            $data['question_image'] = $request->file('question_image')->store('test-questions', 'public');
        }

        // Handle forced choice questions
        if ($data['question_type'] === 'forced_choice' && $request->has('traits')) {
            $traits = array_filter(array_map('trim', explode("\n", $request->traits)));
            // Store instruction in question_text and append traits as JSON
            $instruction = $data['question_text'];
            $data['question_text'] = $instruction . "\n\n<!--TRAITS_JSON:" . json_encode($traits) . "-->";
        }

        $question = TestQuestion::create($data);

        // Create options for multiple choice questions
        if ($question->isMultipleChoice() && $request->has('options')) {
            foreach ($request->options as $optionData) {
                $question->options()->create($optionData);
            }
        }

        return redirect()->route('admin.test-question.index')
            ->with('success', 'Test question created successfully.');
    }

    public function show(TestQuestion $testQuestion)
    {
        $testQuestion->load(['packages', 'options']);
        return view('admin.test-question.show', compact('testQuestion'));
    }

    public function edit(TestQuestion $testQuestion)
    {
        $packages = TestPackage::active()->get();
        $testQuestion->load('options');
        return view('admin.test-question.edit', compact('testQuestion', 'packages'));
    }

    public function update(Request $request, TestQuestion $testQuestion)
    {
        $request->validate([
            'question_text' => 'required|string',
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'question_type' => 'required|in:multiple_choice,essay,scale,video_record,forced_choice',
            'points' => 'required|integer|min:1',
            'order' => 'required|integer|min:1',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*.option_text' => 'required_if:question_type,multiple_choice|string',
            'options.*.is_correct' => 'required_if:question_type,multiple_choice|boolean',
            'options.*.order' => 'required_if:question_type,multiple_choice|integer|min:0'
            // 'traits' => 'required_if:question_type,forced_choice|string'
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('question_image')) {
            // Delete old image
            if ($testQuestion->question_image) {
                Storage::disk('public')->delete($testQuestion->question_image);
            }
            $data['question_image'] = $request->file('question_image')->store('test-questions', 'public');
        }

        // Handle forced choice questions
        if ($data['question_type'] === 'forced_choice' && $request->has('traits')) {
            $traits = array_filter(array_map('trim', explode("\n", $request->traits)));
            // Store instruction in question_text and append traits as JSON
            $instruction = $data['question_text'];
            $data['question_text'] = $instruction . "\n\n<!--TRAITS_JSON:" . json_encode($traits) . "-->";
        }

        $testQuestion->update($data);

        // Update options for multiple choice questions
        if ($testQuestion->isMultipleChoice()) {
            // Delete existing options
            $testQuestion->options()->delete();
            
            // Create new options
            if ($request->has('options')) {
                foreach ($request->options as $optionData) {
                    $testQuestion->options()->create($optionData);
                }
            }
        }

        return redirect()->route('admin.test-question.index')
            ->with('success', 'Test question updated successfully.');
    }

    public function destroy(TestQuestion $testQuestion)
    {
        // Delete image if exists
        if ($testQuestion->question_image) {
            Storage::disk('public')->delete($testQuestion->question_image);
        }
        
        $testQuestion->delete();

        return redirect()->route('admin.test-question.index')
            ->with('success', 'Test question deleted successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            'package_id' => 'nullable|exists:test_packages,id'
        ]);

        try {
            $file = $request->file('import_file');
            $packageId = $request->package_id;
            
            // Read file based on extension
            if ($file->getClientOriginalExtension() === 'csv') {
                $data = array_map('str_getcsv', file($file->getRealPath()));
                $header = array_shift($data);
            } else {
                // Read Excel file
                $spreadsheet = IOFactory::load($file->getRealPath());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                
                $header = array_shift($rows);
                $data = $rows;
            }

            $imported = 0;
            $errors = [];

            foreach ($data as $index => $row) {
                $rowNumber = $index + 2; // +2 because we removed header and arrays are 0-indexed
                
                try {
                    // Map CSV columns to array
                    $rowData = array_combine($header, $row);
                    
                    // Validate required fields
                    if (empty($rowData['question_text']) || empty($rowData['question_type']) || empty($rowData['points'])) {
                        $errors[] = "Row {$rowNumber}: Missing required fields (question_text, question_type, points)";
                        continue;
                    }

                    // Validate question type
                    if (!in_array($rowData['question_type'], ['essay', 'multiple_choice'])) {
                        $errors[] = "Row {$rowNumber}: Invalid question_type. Must be 'essay' or 'multiple_choice'";
                        continue;
                    }

                    // Validate points
                    if (!is_numeric($rowData['points']) || $rowData['points'] < 1) {
                        $errors[] = "Row {$rowNumber}: Points must be a positive number";
                        continue;
                    }

                    // Create question
                    $question = TestQuestion::create([
                        'question_text' => $rowData['question_text'],
                        'question_type' => $rowData['question_type'],
                        'points' => (int) $rowData['points'],
                        'order' => 0 // Will be updated later
                    ]);

                    // Create options for multiple choice questions
                    if ($rowData['question_type'] === 'multiple_choice') {
                        $options = [];
                        $correctAnswer = (int) $rowData['correct_answer'];
                        
                        for ($i = 1; $i <= 4; $i++) {
                            if (!empty($rowData["option_{$i}"])) {
                                $options[] = [
                                    'option_text' => $rowData["option_{$i}"],
                                    'is_correct' => ($i === $correctAnswer),
                                    'order' => $i - 1
                                ];
                            }
                        }

                        if (count($options) < 2) {
                            $errors[] = "Row {$rowNumber}: Multiple choice questions must have at least 2 options";
                            $question->delete();
                            continue;
                        }

                        foreach ($options as $optionData) {
                            $question->options()->create($optionData);
                        }
                    }

                    // Attach to package if specified
                    if ($packageId) {
                        $package = TestPackage::find($packageId);
                        if ($package) {
                            $order = $package->questions()->count() + 1;
                            $package->questions()->attach($question->id, ['order' => $order]);
                            // Update total questions count
                            $package->updateTotalQuestions();
                        }
                    }

                    $imported++;

                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                }
            }

            $message = "Successfully imported {$imported} questions.";
            if (!empty($errors)) {
                $message .= " Errors: " . implode('; ', $errors);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        $packageId = $request->get('package_id');
        
        // Get questions with same logic as index
        $query = TestQuestion::with(['packages', 'options']);
        
        if ($packageId) {
            // Only show questions that are in the package
            $query->whereHas('packages', function($q) use ($packageId) {
                $q->where('test_packages.id', $packageId);
            });
        }
        
        $questions = $query->get();
        
        if ($questions->isEmpty()) {
            return redirect()->back()->with('error', 'No questions found to export.');
        }
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set title
        $title = $packageId ? 'Test Questions - Package Filtered' : 'All Test Questions';
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Set headers
        $headers = [
            'A3' => 'No',
            'B3' => 'Question Text',
            'C3' => 'Type',
            'D3' => 'Points',
            'E3' => 'Packages',
            'F3' => 'Options Count',
            'G3' => 'Options'
        ];
        
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        // Style headers
        $headerRange = 'A3:G3';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE2E2E2');
        $sheet->getStyle($headerRange)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle($headerRange)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Add data
        $row = 4;
        foreach ($questions as $index => $question) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, strip_tags($question->question_text));
            $sheet->setCellValue('C' . $row, ucwords(str_replace('_', ' ', $question->question_type)));
            $sheet->setCellValue('D' . $row, $question->points);
            $sheet->setCellValue('E' . $row, $question->packages->pluck('name')->join(', '));
            
            // Options count
            if ($question->isForcedChoice()) {
                $traits = $question->getForcedChoiceTraits();
                $sheet->setCellValue('F' . $row, count($traits) . ' traits');
            } else {
                $sheet->setCellValue('F' . $row, $question->options->count());
            }
            
            // Options for multiple choice and forced choice
            if ($question->isMultipleChoice()) {
                $options = $question->options->map(function($option) {
                    $marker = $option->is_correct ? '✓' : '';
                    return $marker . ' ' . $option->option_text;
                })->join(' | ');
                $sheet->setCellValue('G' . $row, $options);
            } elseif ($question->isForcedChoice()) {
                $traits = $question->getForcedChoiceTraits();
                $options = implode(' | ', $traits);
                $sheet->setCellValue('G' . $row, $options);
            } else {
                $sheet->setCellValue('G' . $row, '-');
            }
            
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Add borders to data
        $dataRange = 'A3:G' . ($row - 1);
        $sheet->getStyle($dataRange)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);
        
        $writer = new Xlsx($spreadsheet);
        
        $filename = $packageId ? 'test-questions-package-' . $packageId . '.xlsx' : 'test-questions-all.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);
        
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    public function exportPdf(Request $request)
    {
        $packageId = $request->get('package_id');
        
        // Get questions with same logic as index
        $query = TestQuestion::with(['packages', 'options']);
        
        if ($packageId) {
            // Only show questions that are in the package
            $query->whereHas('packages', function($q) use ($packageId) {
                $q->where('test_packages.id', $packageId);
            });
        }
        
        $questions = $query->get();
        $package = $packageId ? TestPackage::find($packageId) : null;
        
        if ($questions->isEmpty()) {
            return redirect()->back()->with('error', 'No questions found to export.');
        }
        
        $data = [
            'questions' => $questions,
            'package' => $package,
            'packageId' => $packageId,
            'title' => $packageId ? 'Test Questions - ' . $package->name : 'All Test Questions'
        ];
        
        $pdf = Pdf::loadView('admin.test-question.export-pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = $packageId ? 'test-questions-package-' . $packageId . '.pdf' : 'test-questions-all.pdf';
        
        return $pdf->download($filename);
    }
}