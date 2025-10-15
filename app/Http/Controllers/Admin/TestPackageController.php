<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestPackage;
use App\Models\TestCategory;
use App\Models\TestQuestion;
use App\Models\TestSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Barryvdh\DomPDF\Facade\Pdf;

class TestPackageController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $categoryId = $request->get('category_id');
        $status = $request->get('status');
        $applicantFlow = $request->get('applicant_flow');

        // Build query with filters
        $query = TestPackage::with(['category', 'questions'])
            ->withCount('sessions');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }

        if ($applicantFlow !== null && $applicantFlow !== '') {
            $query->where('is_applicant_flow', $applicantFlow);
        }

        $packages = $query->paginate(10)->appends($request->query());
        
        return view('admin.test-package.index', compact('packages'));
    }

    public function create()
    {
        $categories = TestCategory::active()->get();
        return view('admin.test-package.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:test_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'show_score_to_user' => 'boolean',
            'is_active' => 'boolean',
            'is_applicant_flow' => 'boolean',
            'is_screening_test' => 'boolean',
            'applicant_flow_order' => 'nullable|integer|min:1|max:10',
            'randomize_questions' => 'boolean'
        ]);

        // Prepare data for creation
        $data = $request->all();
        
        // Handle boolean fields
        $data['is_applicant_flow'] = $request->has('is_applicant_flow');
        $data['is_screening_test'] = $request->has('is_screening_test');
        
        // If screening test is selected, clear flow order
        if ($data['is_screening_test']) {
            $data['applicant_flow_order'] = null;
        }
        
        // If not applicant flow, clear all applicant flow fields
        if (!$data['is_applicant_flow']) {
            $data['is_screening_test'] = false;
            $data['applicant_flow_order'] = null;
        }

        TestPackage::create($data);

        return redirect()->route('admin.test-package.index')
            ->with('success', 'Test package created successfully.');
    }

    public function show(TestPackage $testPackage)
    {
        $testPackage->load(['category', 'questions.options', 'sessions', 'fixedFirstQuestion', 'fixedLastQuestion']);
        return view('admin.test-package.show', compact('testPackage'));
    }

    public function edit(TestPackage $testPackage)
    {
        $categories = TestCategory::active()->get();
        return view('admin.test-package.edit', compact('testPackage', 'categories'));
    }

    public function update(Request $request, TestPackage $testPackage)
    {
        $request->validate([
            'category_id' => 'required|exists:test_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'show_score_to_user' => 'boolean',
            'is_active' => 'boolean',
            'is_applicant_flow' => 'boolean',
            'is_screening_test' => 'boolean',
            'applicant_flow_order' => 'nullable|integer|min:1|max:10',
            'randomize_questions' => 'boolean'
        ]);

        // Prepare data for update
        $data = $request->all();
        
        // Handle boolean fields
        $data['is_applicant_flow'] = $request->has('is_applicant_flow');
        $data['is_screening_test'] = $request->has('is_screening_test');
        
        // If screening test is selected, clear flow order
        if ($data['is_screening_test']) {
            $data['applicant_flow_order'] = null;
        }
        
        // If not applicant flow, clear all applicant flow fields
        if (!$data['is_applicant_flow']) {
            $data['is_screening_test'] = false;
            $data['applicant_flow_order'] = null;
        }

        $testPackage->update($data);

        return redirect()->route('admin.test-package.index')
            ->with('success', 'Test package updated successfully.');
    }

    public function destroy(TestPackage $testPackage)
    {
        $testPackage->delete();

        return redirect()->route('admin.test-package.index')
            ->with('success', 'Test package deleted successfully.');
    }

    // New methods for managing questions in packages
    public function addQuestion(TestPackage $testPackage)
    {
        $existingQuestionIds = $testPackage->questions()->pluck('test_questions.id')->toArray();
        $availableQuestions = TestQuestion::whereNotIn('id', $existingQuestionIds)
            ->with(['options', 'packages'])
            ->orderBy('question_text')
            ->get();
            
        return view('admin.test-package.add-question', compact('testPackage', 'availableQuestions'));
    }

    public function attachQuestion(Request $request, TestPackage $testPackage)
    {
        $request->validate([
            'question_id' => 'required|exists:test_questions,id',
            'order' => 'required|integer|min:1'
        ]);

        // Check if question is already in this package
        if ($testPackage->questions()->where('test_questions.id', $request->question_id)->exists()) {
            return redirect()->back()
                ->with('error', 'This question is already in this package.');
        }

        // Attach question to package
        $testPackage->questions()->attach($request->question_id, [
            'order' => $request->order,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Update total questions count
        $testPackage->update([
            'total_questions' => $testPackage->questions()->count()
        ]);

        return redirect()->route('admin.test-package.show', $testPackage)
            ->with('success', 'Question added to package successfully.');
    }

    public function detachQuestion(TestPackage $testPackage, TestQuestion $question)
    {
        $testPackage->questions()->detach($question->id);

        // Update total questions count
        $testPackage->update([
            'total_questions' => $testPackage->questions()->count()
        ]);

        return redirect()->route('admin.test-package.show', $testPackage)
            ->with('success', 'Question removed from package successfully.');
    }

    public function updateQuestionOrder(Request $request, TestPackage $testPackage)
    {
        $request->validate([
            'question_order' => 'required|array',
            'question_order.*' => 'integer|exists:test_questions,id'
        ]);

        $testPackage->setQuestionOrder($request->question_order);

        return response()->json([
            'success' => true,
            'message' => 'Question order updated successfully.'
        ]);
    }

    public function duplicate(TestPackage $testPackage)
    {
        $newPackage = $testPackage->replicate();
        $newPackage->name = $testPackage->name . ' (Copy)';
        $newPackage->is_active = false; // Set as inactive by default
        $newPackage->save();

        // Copy questions with their order
        $questionIds = $testPackage->questions()->pluck('test_questions.id')->toArray();
        $newPackage->questions()->attach($questionIds);
        
        // Copy question order if exists
        if ($testPackage->question_order) {
            $newPackage->setQuestionOrder($testPackage->question_order);
        }

        // Update total questions count
        $newPackage->updateTotalQuestions();

        return redirect()->route('admin.test-package.edit', $newPackage)
            ->with('success', 'Test package duplicated successfully. Please review and activate if needed.');
    }

    public function randomizeQuestions(TestPackage $testPackage)
    {
        $testPackage->update(['randomize_questions' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'Questions will be randomized for this package.'
        ]);
    }

    public function setCustomOrder(TestPackage $testPackage)
    {
        $testPackage->update(['randomize_questions' => false]);
        
        return response()->json([
            'success' => true,
            'message' => 'Custom question order enabled. You can now drag and drop to reorder questions.'
        ]);
    }

    public function updateQuestionTime(Request $request, TestPackage $testPackage)
    {
        $request->validate([
            'question_id' => 'required|exists:test_questions,id',
            'time_per_question_seconds' => 'nullable|integer|min:1|max:3600'
        ]);

        \Log::info("Updating question time for package {$testPackage->id}, question {$request->question_id}: {$request->time_per_question_seconds} seconds");

        $result = $testPackage->setQuestionTime($request->question_id, $request->time_per_question_seconds);

        // Verify the time was actually saved
        $savedTime = $testPackage->getQuestionTime($request->question_id);
        \Log::info("Verification - saved time for question {$request->question_id}: {$savedTime} seconds");

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Question time updated successfully.' : 'Failed to update question time.',
            'total_duration' => $testPackage->getTotalDuration(),
            'formatted_duration' => $testPackage->getDurationFormattedWithQuestionTime(),
            'saved_time' => $savedTime
        ]);
    }

    public function bulkUpdateQuestionTimes(Request $request, TestPackage $testPackage)
    {
        $request->validate([
            'question_times' => 'required|array',
            'question_times.*' => 'nullable|integer|min:1|max:3600'
        ]);

        foreach ($request->question_times as $questionId => $timeInSeconds) {
            $testPackage->setQuestionTime($questionId, $timeInSeconds);
        }

        return response()->json([
            'success' => true,
            'message' => 'Question times updated successfully.',
            'total_duration' => $testPackage->getTotalDuration(),
            'formatted_duration' => $testPackage->getDurationFormattedWithQuestionTime()
        ]);
    }

    public function toggleTimePerQuestion(Request $request, TestPackage $testPackage)
    {
        $request->validate([
            'enable_time_per_question' => 'required|boolean'
        ]);

        $testPackage->update([
            'enable_time_per_question' => $request->enable_time_per_question
        ]);

        return response()->json([
            'success' => true,
            'message' => $request->enable_time_per_question 
                ? 'Time per question enabled. You can now set individual question times.'
                : 'Time per question disabled. Using package total duration.',
            'enable_time_per_question' => $testPackage->enable_time_per_question,
            'formatted_duration' => $testPackage->getDurationFormattedWithQuestionTime()
        ]);
    }

    public function updateFixedQuestion(Request $request, TestPackage $testPackage)
    {
        $request->validate([
            'fix_first_question' => 'sometimes|boolean',
            'fix_last_question' => 'sometimes|boolean',
            'fixed_first_question_id' => 'nullable|exists:test_questions,id',
            'fixed_last_question_id' => 'nullable|exists:test_questions,id'
        ]);

        // Validate that fixed questions belong to this package
        if ($request->filled('fixed_first_question_id')) {
            if (!$testPackage->questions()->where('test_questions.id', $request->fixed_first_question_id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected first question does not belong to this package.'
                ], 422);
            }
        }

        if ($request->filled('fixed_last_question_id')) {
            if (!$testPackage->questions()->where('test_questions.id', $request->fixed_last_question_id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected last question does not belong to this package.'
                ], 422);
            }
        }

        // Prevent same question being fixed as both first and last
        if ($request->filled('fixed_first_question_id') && $request->filled('fixed_last_question_id')) {
            if ($request->fixed_first_question_id == $request->fixed_last_question_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'First and last question cannot be the same.'
                ], 422);
            }
        }

        // Ensure only one question can be selected for each type
        if ($request->filled('fixed_first_question_id') && $request->fix_first_question) {
            // Clear any other first question selections
            $testPackage->update(['fixed_first_question_id' => null]);
        }

        if ($request->filled('fixed_last_question_id') && $request->fix_last_question) {
            // Clear any other last question selections
            $testPackage->update(['fixed_last_question_id' => null]);
        }

        $updateData = [];
        
        if ($request->has('fix_first_question')) {
            $updateData['fix_first_question'] = $request->fix_first_question;
            if (!$request->fix_first_question) {
                $updateData['fixed_first_question_id'] = null;
            }
        }
        
        if ($request->has('fix_last_question')) {
            $updateData['fix_last_question'] = $request->fix_last_question;
            if (!$request->fix_last_question) {
                $updateData['fixed_last_question_id'] = null;
            }
        }
        
        if ($request->has('fixed_first_question_id')) {
            $updateData['fixed_first_question_id'] = $request->fixed_first_question_id;
        }
        
        if ($request->has('fixed_last_question_id')) {
            $updateData['fixed_last_question_id'] = $request->fixed_last_question_id;
        }

        $testPackage->update($updateData);

        $message = 'Fixed question settings updated successfully.';
        if ($testPackage->fix_first_question && $testPackage->fix_last_question) {
            $message .= ' First and last questions are now fixed in position.';
        } elseif ($testPackage->fix_first_question) {
            $message .= ' First question is now fixed in position.';
        } elseif ($testPackage->fix_last_question) {
            $message .= ' Last question is now fixed in position.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'fix_first_question' => $testPackage->fix_first_question,
            'fix_last_question' => $testPackage->fix_last_question,
            'fixed_first_question_id' => $testPackage->fixed_first_question_id,
            'fixed_last_question_id' => $testPackage->fixed_last_question_id
        ]);
    }

    /**
     * Generate test URL and QR code for public access
     */
    public function generateTestLink(TestPackage $testPackage)
    {
        try {
            // Create a public test session (without applicant)
            $session = TestSession::create([
                'package_id' => $testPackage->id,
                'applicant_id' => null, // Public access
                'user_id' => null, // Will be filled when user takes the test
                'job_position' => null, // No specific job position for admin-generated tests
                'status' => 'pending',
                'access_token' => null, // Will be generated
                'expires_at' => Carbon::now()->addDay() // 1 day validity
            ]);

            // Generate access token
            $accessToken = $session->generateAccessToken();

            // Generate test URL (using regular route that requires login)
            $testUrl = route('test.take', ['session' => $session, 'token' => $accessToken]);

            // Generate QR code (using SVG format to avoid imagick dependency)
            $qrCode = QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->generate($testUrl);

            return response()->json([
                'success' => true,
                'test_url' => $testUrl,
                'qr_code' => 'data:image/svg+xml;base64,' . base64_encode($qrCode),
                'expires_at' => $session->expires_at->format('Y-m-d H:i:s'),
                'session_id' => $session->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating test link: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate QR code for existing test session
     */
    public function generateQRCode(TestSession $session)
    {
        try {
            $token = $session->access_token;
            if (!$token) {
                $token = $session->generateAccessToken();
            }
            
            $testUrl = route('test.take', ['session' => $session, 'token' => $token]);
            
            $qrCode = QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->generate($testUrl);
            
            return response($qrCode)
                ->header('Content-Type', 'image/svg+xml')
                ->header('Content-Disposition', 'inline; filename="test-qr-' . $session->id . '.svg"');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate public package link for sharing
     */
    public function generatePublicPackageLink(TestPackage $testPackage)
    {
        try {
            // Generate public package URL (no session needed, just package info)
            $publicUrl = route('test.public-package', ['package' => $testPackage]);
            
            // Generate QR code for the public package link
            $qrCode = QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->generate($publicUrl);
            
            return response()->json([
                'success' => true,
                'public_url' => $publicUrl,
                'qr_code' => 'data:image/svg+xml;base64,' . base64_encode($qrCode),
                'package_name' => $testPackage->name,
                'package_description' => $testPackage->description,
                'duration_minutes' => $testPackage->duration_minutes,
                'total_questions' => $testPackage->total_questions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating public package link: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            // Get filter parameters
            $categoryId = $request->get('category_id');
            $status = $request->get('status');
            $applicantFlow = $request->get('applicant_flow');

            // Build query with filters
            $query = TestPackage::with(['category', 'questions'])
                ->withCount('sessions');

            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            if ($status !== null && $status !== '') {
                $query->where('is_active', $status);
            }

            if ($applicantFlow !== null && $applicantFlow !== '') {
                $query->where('is_applicant_flow', $applicantFlow);
            }

            $packages = $query->get();

            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set title
            $sheet->setTitle('Test Packages Export');

            // Set headers
            $headers = [
                'ID',
                'Package Name',
                'Category',
                'Duration (Minutes)',
                'Total Questions',
                'Passing Score (%)',
                'Sessions Count',
                'Applicant Flow',
                'Screening Test',
                'Flow Order',
                'Status',
                'Show Score to User',
                'Randomize Questions',
                'Created At',
                'Updated At'
            ];

            $col = 1;
            foreach ($headers as $header) {
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '1', $header);
                $col++;
            }

            // Style headers
            $headerRange = 'A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers)) . '1';
            $sheet->getStyle($headerRange)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ]);

            // Add data
            $row = 2;
            foreach ($packages as $package) {
                $sheet->setCellValue('A' . $row, $package->id);
                $sheet->setCellValue('B' . $row, $package->name);
                $sheet->setCellValue('C' . $row, $package->category->name);
                $sheet->setCellValue('D' . $row, $package->duration_minutes);
                $sheet->setCellValue('E' . $row, $package->total_questions);
                $sheet->setCellValue('F' . $row, $package->passing_score);
                $sheet->setCellValue('G' . $row, $package->sessions_count);
                $sheet->setCellValue('H' . $row, $package->is_applicant_flow ? 'Yes' : 'No');
                $sheet->setCellValue('I' . $row, $package->is_screening_test ? 'Yes' : 'No');
                $sheet->setCellValue('J' . $row, $package->applicant_flow_order ?? 'N/A');
                $sheet->setCellValue('K' . $row, $package->is_active ? 'Active' : 'Inactive');
                $sheet->setCellValue('L' . $row, $package->show_score_to_user ? 'Yes' : 'No');
                $sheet->setCellValue('M' . $row, $package->randomize_questions ? 'Yes' : 'No');
                $sheet->setCellValue('N' . $row, $package->created_at->format('Y-m-d H:i:s'));
                $sheet->setCellValue('O' . $row, $package->updated_at->format('Y-m-d H:i:s'));
                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'O') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Add borders to data
            $dataRange = 'A1:O' . ($row - 1);
            $sheet->getStyle($dataRange)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ]);

            // Create filename with filters
            $filename = 'test-packages-export';
            if ($categoryId) {
                $category = TestCategory::find($categoryId);
                $filename .= '-' . strtolower(str_replace(' ', '-', $category->name));
            }
            if ($status !== null && $status !== '') {
                $filename .= '-' . ($status ? 'active' : 'inactive');
            }
            if ($applicantFlow !== null && $applicantFlow !== '') {
                $filename .= '-' . ($applicantFlow ? 'applicant-flow' : 'general');
            }
            $filename .= '-' . date('Y-m-d-H-i-s') . '.xlsx';

            // Create writer and save
            $writer = new Xlsx($spreadsheet);
            
            // Set headers for download
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error exporting Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            // Get filter parameters
            $categoryId = $request->get('category_id');
            $status = $request->get('status');
            $applicantFlow = $request->get('applicant_flow');

            // Build query with filters
            $query = TestPackage::with(['category', 'questions'])
                ->withCount('sessions');

            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            if ($status !== null && $status !== '') {
                $query->where('is_active', $status);
            }

            if ($applicantFlow !== null && $applicantFlow !== '') {
                $query->where('is_applicant_flow', $applicantFlow);
            }

            $packages = $query->get();

            // Get filter info for display
            $filterInfo = [];
            if ($categoryId) {
                $category = TestCategory::find($categoryId);
                $filterInfo[] = 'Category: ' . $category->name;
            }
            if ($status !== null && $status !== '') {
                $filterInfo[] = 'Status: ' . ($status ? 'Active' : 'Inactive');
            }
            if ($applicantFlow !== null && $applicantFlow !== '') {
                $filterInfo[] = 'Type: ' . ($applicantFlow ? 'Applicant Flow' : 'General Test');
            }

            $data = [
                'packages' => $packages,
                'filterInfo' => $filterInfo,
                'exportDate' => now()->format('d M Y H:i:s')
            ];

            $pdf = Pdf::loadView('admin.test-package.export-pdf', $data);
            $pdf->setPaper('A4', 'landscape');

            // Create filename with filters
            $filename = 'test-packages-export';
            if ($categoryId) {
                $category = TestCategory::find($categoryId);
                $filename .= '-' . strtolower(str_replace(' ', '-', $category->name));
            }
            if ($status !== null && $status !== '') {
                $filename .= '-' . ($status ? 'active' : 'inactive');
            }
            if ($applicantFlow !== null && $applicantFlow !== '') {
                $filename .= '-' . ($applicantFlow ? 'applicant-flow' : 'general');
            }
            $filename .= '-' . date('Y-m-d-H-i-s') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error exporting PDF: ' . $e->getMessage());
        }
    }
}