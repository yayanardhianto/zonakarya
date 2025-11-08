<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestSession;
use App\Models\TestPackage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Barryvdh\DomPDF\Facade\Pdf;

class TestSessionController extends Controller
{
    public function index(Request $request)
    {
        $query = TestSession::with(['package.category', 'applicant', 'user', 'jobVacancy']);
        
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by package
        if ($request->filled('package_id')) {
            $query->where('package_id', $request->package_id);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $sessions = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());
        $packages = TestPackage::active()->get();
        
        return view('admin.test-session.index', compact('sessions', 'packages'));
    }

    public function show(TestSession $testSession)
    {
        $testSession->load([
            'package.category',
            'applicant',
            'jobVacancy',
            'answers.question.options'
        ]);
        
        return view('admin.test-session.show', compact('testSession'));
    }

    public function destroy(TestSession $testSession)
    {
        $testSession->delete();

        return redirect()->route('admin.test-session.index')
            ->with('success', 'Test session deleted successfully.');
    }

    public function gradeEssay(Request $request, TestSession $testSession)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:test_questions,id',
            'answers.*.points_earned' => 'required|integer|min:0'
        ]);

        foreach ($request->answers as $answerData) {
            $answer = $testSession->answers()
                ->where('question_id', $answerData['question_id'])
                ->first();
                
            if ($answer) {
                $answer->update([
                    'points_earned' => $answerData['points_earned'],
                    'is_correct' => $answerData['points_earned'] > 0
                ]);
            }
        }

        // Recalculate total score
        $this->calculateSessionScore($testSession);

        return redirect()->route('admin.test-session.show', $testSession)
            ->with('success', 'Essay answers graded successfully.');
    }

    private function calculateSessionScore(TestSession $testSession)
    {
        // Check if test contains questions that require manual grading
        $hasEssayQuestions = $testSession->package->questions()
            ->where('question_type', 'essay')
            ->exists();
            
        $hasScaleQuestions = $testSession->package->questions()
            ->where('question_type', 'scale')
            ->exists();
            
        $hasForcedChoiceQuestions = $testSession->package->questions()
            ->where('question_type', 'forced_choice')
            ->exists();
            
        $hasVideoQuestions = $testSession->package->questions()
            ->where('question_type', 'video_record')
            ->exists();
        
        // If test contains questions that require manual grading, don't calculate overall score
        if ($hasEssayQuestions || $hasScaleQuestions || $hasForcedChoiceQuestions || $hasVideoQuestions) {
            $testSession->update([
                'score' => null,
                'is_passed' => null,
                'notes' => 'Test contains questions that require manual grading - no overall score calculated'
            ]);
        } else {
            // Only calculate score for multiple choice questions
            $totalPoints = $testSession->answers()->sum('points_earned');
            $maxPoints = $testSession->package->questions()->sum('points');
            
            if ($maxPoints > 0) {
                $score = round(($totalPoints / $maxPoints) * 100);
                $isPassed = $score >= $testSession->package->passing_score;
                
                $testSession->update([
                    'score' => $score,
                    'is_passed' => $isPassed,
                    'notes' => null
                ]);
            }
        }
    }

    public function getApplicantTestDetail($applicantId, $testType)
    {
        $applicant = \App\Models\Applicant::findOrFail($applicantId);
        
        // Tentukan package berdasarkan test type
        if ($testType === 'screening') {
            $package = \App\Models\TestPackage::where('is_screening_test', true)->first();
        } elseif ($testType === 'psychology') {
            $package = \App\Models\TestPackage::where('applicant_flow_order', 2)->first();
        } else {
            abort(404, 'Invalid test type');
        }
        
        if (!$package) {
            abort(404, 'Test package not found');
        }
        
        $testSession = TestSession::where('applicant_id', $applicantId)
            ->where('package_id', $package->id)
            ->where('status', 'completed')
            ->with(['package', 'answers.question.options'])
            ->first();
        
        if (!$testSession) {
            abort(404, 'Test session not found');
        }
        
        return view('admin.test-session.applicant-detail', compact('testSession'));
    }

    public function exportExcel(Request $request)
    {
        try {
            // Get filter parameters
            $status = $request->get('status');
            $packageId = $request->get('package_id');
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');

            // Build query with filters
            $query = TestSession::with([
                'package.category', 
                'applicant', 
                'user', 
                'jobVacancy',
                'answers.question.options' // Load answers with questions and options
            ]);

            if ($request->filled('status')) {
                $query->where('status', $status);
            }

            if ($request->filled('package_id')) {
                $query->where('package_id', $packageId);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $dateFrom);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $dateTo);
            }

            $sessions = $query->orderBy('created_at', 'desc')->get();
            
            // Find maximum number of questions across all sessions to determine column count
            $maxQuestions = 0;
            foreach ($sessions as $session) {
                $questionCount = $session->answers->count();
                if ($questionCount > $maxQuestions) {
                    $maxQuestions = $questionCount;
                }
            }

            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set title
            $sheet->setTitle('Test Sessions Export');

            // Set headers - base headers + dynamic question/answer columns
            $headers = [
                'ID',
                'Applicant/User Name',
                'Email',
                'Package Name',
                'Category',
                'Job Position',
                'Status',
                'Score (%)',
                'Passed',
                'Progress (%)',
                'Started At',
                'Completed At',
                'Duration (Minutes)',
                'Created At'
            ];
            
            // Add question and answer columns dynamically
            for ($i = 1; $i <= $maxQuestions; $i++) {
                $headers[] = "Test Question #{$i}";
                $headers[] = "Test Answer #{$i}";
            }

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
            foreach ($sessions as $session) {
                $col = 1;
                
                // Base session data
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $session->id);
                
                // Applicant/User Name
                if ($session->applicant) {
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $session->applicant->name);
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $session->applicant->email);
                } elseif ($session->user) {
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $session->user->name);
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $session->user->email);
                } else {
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, 'N/A');
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, 'N/A');
                }
                
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $session->package->name);
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $session->package->category->name);
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $session->jobVacancy ? $session->jobVacancy->position : 'N/A');
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, ucfirst($session->status));
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $session->score ?? 'N/A');
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $session->is_passed ? 'Yes' : ($session->score !== null ? 'No' : 'N/A'));
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $session->isInProgress() ? $session->progress_percentage . '%' : 'N/A');
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $session->started_at ? $session->started_at->format('Y-m-d H:i:s') : 'Not Started');
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $session->completed_at ? $session->completed_at->format('Y-m-d H:i:s') : 'Not Completed');
                
                // Calculate duration
                if ($session->started_at && $session->completed_at) {
                    $duration = $session->started_at->diffInMinutes($session->completed_at);
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $duration);
                } else {
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, 'N/A');
                }
                
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $session->created_at->format('Y-m-d H:i:s'));
                
                // Add questions and answers
                // Get answers ordered by question order (assuming questions have order field or by ID)
                $answers = $session->answers->sortBy(function($answer) {
                    return $answer->question->id ?? 0;
                });
                
                $answerIndex = 0;
                foreach ($answers as $answer) {
                    $question = $answer->question;
                    
                    // Question text
                    $questionText = $question ? strip_tags($question->question_text ?? 'N/A') : 'N/A';
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $questionText);
                    
                    // Answer text - handle different answer types
                    $answerText = '';
                    
                    // Check for video answer
                    if (!empty($answer->video_answer)) {
                        // Generate full URL for video
                        $videoPath = trim($answer->video_answer);
                        
                        // If already a full URL (http/https), use it directly
                        if (strpos($videoPath, 'http://') === 0 || strpos($videoPath, 'https://') === 0) {
                            $videoUrl = $videoPath;
                        }
                        // If it starts with /storage/, it's already a public URL path - make it absolute
                        elseif (strpos($videoPath, '/storage/') === 0 || strpos($videoPath, 'storage/') === 0) {
                            // Normalize to start with /
                            $normalizedPath = '/' . ltrim($videoPath, '/');
                            $videoUrl = url($normalizedPath);
                        }
                        // If it's a storage path like "public/test_videos/..."
                        elseif (strpos($videoPath, 'public/test_videos/') === 0) {
                            // Remove 'public/' prefix to get relative path
                            $relativePath = str_replace('public/', '', $videoPath);
                            // Use Storage::url which returns /storage/test_videos/...
                            $videoUrl = Storage::url($relativePath);
                            // Make it absolute URL
                            $videoUrl = url($videoUrl);
                        }
                        // If it's just "test_videos/..." (without public/)
                        elseif (strpos($videoPath, 'test_videos/') === 0) {
                            // Use Storage::url directly
                            $videoUrl = Storage::url($videoPath);
                            // Make it absolute URL
                            $videoUrl = url($videoUrl);
                        }
                        // If it's just a filename
                        else {
                            // Assume it's in test_videos directory
                            $videoUrl = Storage::url('test_videos/' . ltrim($videoPath, '/'));
                            // Make it absolute URL
                            $videoUrl = url($videoUrl);
                        }
                        
                        $answerText = $videoUrl;
                    }
                    // Check for text answer
                    elseif (!empty($answer->answer_text)) {
                        $answerText = strip_tags($answer->answer_text);
                    }
                    // Check for selected option (multiple choice)
                    elseif ($answer->selectedOption) {
                        $optionText = strip_tags($answer->selectedOption->option_text ?? 'N/A');
                        // Add indicator for correct/incorrect answer
                        if ($answer->is_correct !== null) {
                            if ($answer->is_correct) {
                                $answerText = $optionText . ' [CORRECT]';
                            } else {
                                $answerText = $optionText . ' [WRONG]';
                            }
                        } else {
                            $answerText = $optionText;
                        }
                    }
                    // Check for scale value
                    elseif (isset($answer->scale_value)) {
                        $answerText = $answer->scale_value;
                    }
                    else {
                        $answerText = 'No Answer';
                    }
                    
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $answerText);
                    
                    $answerIndex++;
                }
                
                // Fill remaining question/answer columns with empty values
                for ($i = $answerIndex; $i < $maxQuestions; $i++) {
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, '');
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, '');
                }
                
                $row++;
            }

            // Auto-size columns
            $totalColumns = count($headers);
            for ($col = 1; $col <= $totalColumns; $col++) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
                // Set max width for question/answer columns to prevent extremely wide columns
                if ($col > 14) { // After base columns
                    $sheet->getColumnDimension($columnLetter)->setWidth(50);
                }
            }

            // Add borders to data
            $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalColumns);
            $dataRange = 'A1:' . $lastColumn . ($row - 1);
            $sheet->getStyle($dataRange)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ]);

            // Create filename with filters
            $filename = 'test-sessions-export';
            if ($status) {
                $filename .= '-' . $status;
            }
            if ($packageId) {
                $package = TestPackage::find($packageId);
                $filename .= '-' . strtolower(str_replace(' ', '-', $package->name));
            }
            if ($dateFrom) {
                $filename .= '-from-' . $dateFrom;
            }
            if ($dateTo) {
                $filename .= '-to-' . $dateTo;
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
            $status = $request->get('status');
            $packageId = $request->get('package_id');
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');

            // Build query with filters
            $query = TestSession::with(['package.category', 'applicant', 'user', 'jobVacancy']);

            if ($request->filled('status')) {
                $query->where('status', $status);
            }

            if ($request->filled('package_id')) {
                $query->where('package_id', $packageId);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $dateFrom);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $dateTo);
            }

            $sessions = $query->orderBy('created_at', 'desc')->get();

            // Get filter info for display
            $filterInfo = [];
            if ($status) {
                $filterInfo[] = 'Status: ' . ucfirst($status);
            }
            if ($packageId) {
                $package = TestPackage::find($packageId);
                $filterInfo[] = 'Package: ' . $package->name;
            }
            if ($dateFrom) {
                $filterInfo[] = 'From Date: ' . $dateFrom;
            }
            if ($dateTo) {
                $filterInfo[] = 'To Date: ' . $dateTo;
            }

            $data = [
                'sessions' => $sessions,
                'filterInfo' => $filterInfo,
                'exportDate' => now()->format('d M Y H:i:s')
            ];

            $pdf = Pdf::loadView('admin.test-session.export-pdf', $data);
            $pdf->setPaper('A4', 'landscape');

            // Create filename with filters
            $filename = 'test-sessions-export';
            if ($status) {
                $filename .= '-' . $status;
            }
            if ($packageId) {
                $package = TestPackage::find($packageId);
                $filename .= '-' . strtolower(str_replace(' ', '-', $package->name));
            }
            if ($dateFrom) {
                $filename .= '-from-' . $dateFrom;
            }
            if ($dateTo) {
                $filename .= '-to-' . $dateTo;
            }
            $filename .= '-' . date('Y-m-d-H-i-s') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error exporting PDF: ' . $e->getMessage());
        }
    }
}