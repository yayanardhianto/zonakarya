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

class TestPackageController extends Controller
{
    public function index()
    {
        $packages = TestPackage::with(['category', 'questions'])
            ->withCount('sessions')
            ->paginate(10);
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
        $testPackage->load(['category', 'questions.options', 'sessions']);
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
}