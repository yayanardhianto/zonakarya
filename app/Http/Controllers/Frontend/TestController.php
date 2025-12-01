<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TestPackage;
use App\Models\TestSession;
use App\Models\TestQuestion;
use App\Models\TestAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TestController extends Controller
{
    public function index()
    {
        $packages = TestPackage::active()
            ->with(['category', 'questions'])
            ->get();
            
        return view('frontend.test.index', compact('packages'));
    }

    public function start(Request $request, TestPackage $package)
    {
        if (!$package->is_active) {
            return redirect()->route('test.index')
                ->with('error', 'Test package is not available.');
        }

        // For non-applicant flow tests, applicant_id should be null
        $applicantId = null;
        $jobPosition = null;
        
        // Only set applicant_id if this is an applicant flow test
        if ($package->is_applicant_flow) {
            $applicant = \App\Models\Applicant::where('user_id', Auth::id())->first();
            if ($applicant) {
                $applicantId = $applicant->id;
                $latestApplication = $applicant->applications()->latest()->first();
                if ($latestApplication && $latestApplication->jobVacancy) {
                    $jobPosition = $latestApplication->jobVacancy->position;
                }
            }
        }
        $existingSessionQuery = TestSession::where('package_id', $package->id)
            ->whereIn('status', ['pending', 'in_progress']);

        if ($applicantId) {
            $existingSessionQuery->where('applicant_id', $applicantId);
        } else {
            $existingSessionQuery->where('user_id', Auth::id());
        }

        $existingSession = $existingSessionQuery->first();
        // Check for existing session
        // $existingSession = TestSession::where('applicant_id', $applicantId)
        //     ->where('package_id', $package->id)
        //     ->whereIn('status', ['pending', 'in_progress'])
        //     ->first();

        if ($existingSession) {
            $accessToken = $existingSession->access_token ?: $existingSession->generateAccessToken();
            return redirect()->route('test.take', ['session' => $existingSession, 'token' => $accessToken]);
        }

        // Create new session
        $session = TestSession::create([
            'user_id' => Auth::id(),
            'applicant_id' => $applicantId, // null for non-applicant flow tests
            'package_id' => $package->id,
            'job_position' => $jobPosition,
            'status' => 'pending'
        ]);

        // Generate access token
        $accessToken = $session->generateAccessToken();

        return redirect()->route('test.take', ['session' => $session, 'token' => $accessToken]);
    }

    public function generateQRCode(TestSession $session)
    {
        $token = $session->access_token;
        if (!$token) {
            $token = $session->generateAccessToken();
        }
        
        $testUrl = route('test.take', ['session' => $session, 'token' => $token]);
        
        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->generate($testUrl);
        
        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'inline; filename="test-qr-' . $session->id . '.png"');
    }

    public function take(Request $request, TestSession $session)
    {
        $token = $request->get('token');
        
        // Check if user is authorized
        $applicant = $session->applicant;
        
        // For public access (admin-generated links), allow access without user authentication
        if (!$applicant) {
            // This is a public test session created by admin
            // Check if current user has an applicant record and link it
            if (Auth::check()) {
                $userApplicant = \App\Models\Applicant::where('user_id', Auth::id())->first();
                if ($userApplicant) {
                    // Link this test session to the user's applicant record
                    $session->update([
                        'applicant_id' => $userApplicant->id,
                        'user_id' => Auth::id() // Also link with user account
                    ]);
                    $applicant = $userApplicant;
                } else {
                    // No applicant record, but user is logged in - link with user account
                    $session->update(['user_id' => Auth::id()]);
                }
            }
            // Allow access without user authentication
        } elseif (!$applicant->user_id || $applicant->user_id !== Auth::id()) {
            // This is a regular test session, require user authentication
            abort(403, 'Unauthorized access to test session.');
        }

        // Handle token issues
        if (!$session->isTokenValid($token)) {
            // If session is completed, allow access to result page
            if ($session->isCompleted()) {
                $newToken = $session->generateAccessToken();
                return redirect()->route('test.result', ['session' => $session, 'token' => $newToken]);
            }
            
            // If token is expired or invalid, regenerate it
            if (!$session->access_token || $session->access_token !== $token) {
                $newToken = $session->generateAccessToken();
                return redirect()->route('test.take', ['session' => $session, 'token' => $newToken]);
            }
            
            // If token is expired, regenerate it
            if ($session->expires_at && Carbon::now()->gt($session->expires_at)) {
                $newToken = $session->generateAccessToken();
                return redirect()->route('test.take', ['session' => $session, 'token' => $newToken]);
            }
            
            // If all else fails, show error page
            return view('frontend.test.token-error', ['sessionId' => $session->id]);
        }

        if ($session->isCompleted() || $session->isExpired()) {
            return redirect()->route('test.result', ['session' => $session, 'token' => $token]);
        }

        // Start session if not started
        if ($session->isPending()) {
            $session->update([
                'status' => 'in_progress',
                'started_at' => Carbon::now()
            ]);
        }

        $session->load(['package.questions.options', 'answers']);
        
        // Get current question (first unanswered question or specific question)
        $questionNumber = $request->get('question', 1);
        $answeredQuestionIds = $session->answers()->pluck('question_id')->toArray();
        
        // Get all questions ordered by test_package_question.order or randomized
        if ($session->package->randomize_questions) {
            // Check if we already have a randomized order stored in this session
            if ($session->question_order && is_array($session->question_order)) {
                // Use stored randomized order for consistency during this session
                $allQuestions = $session->package->questions()
                    ->whereIn('test_questions.id', $session->question_order)
                    ->get()
                    ->sortBy(function($question) use ($session) {
                        return array_search($question->id, $session->question_order);
                    })
                    ->values();
            } else {
                // First time accessing this session - use package's getOrderedQuestions method
                $allQuestions = $session->package->getOrderedQuestions();
                
                // Store the randomized order in session for consistency during this session
                $session->update([
                    'question_order' => $allQuestions->pluck('id')->toArray()
                ]);
            }
        } else {
            // Use package question order
            $allQuestions = $session->package->questions()
                ->orderBy('test_package_question.order')
                ->get();
        }
        
        if ($questionNumber > 1) {
            // Load specific question by position in ordered list
            if ($questionNumber <= $allQuestions->count()) {
                $currentQuestion = $allQuestions->get($questionNumber - 1);
            } else {
                $currentQuestion = null;
            }
        } else {
            // Load first unanswered question
            $currentQuestion = $allQuestions
                ->whereNotIn('id', $answeredQuestionIds)
                ->first();
        }

        if (!$currentQuestion) {
            // Check if all questions are actually answered
            $totalQuestions = $allQuestions->count();
            $answeredQuestions = $session->answers()->count();
            
            if ($answeredQuestions >= $totalQuestions) {
                // All questions answered, complete the test
                $this->completeTest($session);
                return redirect()->route('test.result', ['session' => $session, 'token' => $token]);
            } else {
                // There are unanswered questions, but we can't find them
                // This might be due to order issues, let's try to find any unanswered question
                $currentQuestion = $allQuestions
                    ->whereNotIn('id', $answeredQuestionIds)
                    ->first();
                
                if (!$currentQuestion) {
                    // Still no question found, complete the test
                    $this->completeTest($session);
                    return redirect()->route('test.result', ['session' => $session, 'token' => $token]);
                }
            }
        }

        return view('frontend.test.take', compact('session', 'currentQuestion', 'questionNumber'));
    }

    public function answer(Request $request, TestSession $session)
    {
        $token = $request->get('token');
        
        // Validate access token
        if (!$session->isTokenValid($token)) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired test session access token.'], 403);
        }

        // Check authorization
        $applicant = $session->applicant;
        
        // For public access (admin-generated links), allow access without user authentication
        if (!$applicant) {
            // This is a public test session created by admin
            // Check if current user has an applicant record and link it
            if (Auth::check()) {
                $userApplicant = \App\Models\Applicant::where('user_id', Auth::id())->first();
                if ($userApplicant) {
                    // Link this test session to the user's applicant record
                    $session->update(['applicant_id' => $userApplicant->id]);
                    $applicant = $userApplicant;
                }
            }
            // Allow access without user authentication
        } elseif (!$applicant->user_id || $applicant->user_id !== Auth::id()) {
            // This is a regular test session, require user authentication
            return response()->json(['success' => false, 'message' => 'Unauthorized access to test session.'], 403);
        }

        if ($session->isCompleted() || $session->isExpired()) {
            return response()->json(['success' => false, 'redirect' => route('test.result', ['session' => $session, 'token' => $token])]);
        }

        $request->validate([
            'question_id' => 'required|exists:test_questions,id',
            'answer_text' => 'nullable|string',
            'selected_option_id' => 'nullable|exists:test_question_options,id',
            'scale_value' => 'nullable|integer|min:1|max:10',
            'video_answer' => 'nullable|string',
            'video_text_fallback' => 'nullable|string',
            'forced_choice_most' => 'nullable|string',
            'forced_choice_least' => 'nullable|string'
        ]);

        $question = TestQuestion::findOrFail($request->question_id);
        
        // Use database transaction to prevent race conditions
        try {
            DB::beginTransaction();
            
            // Lock the session row to prevent concurrent updates
            $session = TestSession::lockForUpdate()->findOrFail($session->id);
            
            // Check if session is still valid
            if ($session->isCompleted() || $session->isExpired()) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Test session is already completed or expired.'], 400);
            }
            
            // Check if already answered (with lock to prevent duplicate)
            $existingAnswer = $session->answers()
                ->where('question_id', $question->id)
                ->lockForUpdate()
                ->first();

            $answerData = [
                'question_id' => $question->id,
                'answered_at' => Carbon::now()
            ];

            if ($question->isMultipleChoice()) {
                $answerData['selected_option_id'] = $request->selected_option_id;
                
                if ($request->selected_option_id) {
                    $selectedOption = $question->options()
                        ->where('id', $request->selected_option_id)
                        ->first();
                        
                    if ($selectedOption) {
                        $answerData['is_correct'] = $selectedOption->is_correct;
                        $answerData['points_earned'] = $selectedOption->is_correct ? $question->points : 0;
                    }
                }
            } elseif ($question->isScale()) {
                $answerData['scale_value'] = $request->scale_value;
                // Scale questions are automatically scored based on value
                $answerData['is_correct'] = true; // All scale answers are considered valid
                $answerData['points_earned'] = $request->scale_value ? round(($request->scale_value / 10) * $question->points) : 0;
            } elseif ($question->isVideoRecord()) {
                // Handle video answer or text fallback
                if ($request->video_answer) {
                    $answerData['video_answer'] = $request->video_answer;
                    $answerData['points_earned'] = $question->points;
                } elseif ($request->video_text_fallback) {
                    // Store text fallback as answer_text for video questions
                    $answerData['answer_text'] = $request->video_text_fallback;
                    $answerData['points_earned'] = $question->points * 0.8; // Slightly lower points for text fallback
                }
                // Video record questions are automatically scored
                $answerData['is_correct'] = true; // All video answers (or text fallback) are considered valid
            } elseif ($question->isForcedChoice()) {
                // Handle forced choice answers
                if ($request->forced_choice_most !== null && $request->forced_choice_least !== null) {
                    $forcedChoiceData = [
                        'most_similar' => $request->forced_choice_most,
                        'least_similar' => $request->forced_choice_least
                    ];
                    $answerData['answer_text'] = json_encode($forcedChoiceData);
                    $answerData['is_correct'] = true; // All forced choice answers are considered valid
                    $answerData['points_earned'] = $question->points;
                } else {
                    $answerData['answer_text'] = null;
                    $answerData['is_correct'] = false;
                    $answerData['points_earned'] = 0;
                }
            } else {
                $answerData['answer_text'] = $request->answer_text;
                // Essay questions need manual grading
                $answerData['is_correct'] = null;
                $answerData['points_earned'] = 0;
            }

            if ($existingAnswer) {
                $existingAnswer->update($answerData);
            } else {
                $session->answers()->create($answerData);
            }
            
            DB::commit();
            
            // Auto-save progress
            return response()->json(['success' => true, 'message' => 'Answer saved successfully']);
            
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            \Log::error('Test Answer: Database error', [
                'error' => $e->getMessage(),
                'session_id' => $session->id,
                'question_id' => $request->question_id,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['success' => false, 'message' => 'Error saving your answer. Please try again.'], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Test Answer: Unexpected error', [
                'error' => $e->getMessage(),
                'session_id' => $session->id,
                'question_id' => $request->question_id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['success' => false, 'message' => 'Error saving your answer. Please try again.'], 500);
        }
    }

    public function result(Request $request, TestSession $session)
    {
        $token = $request->get('token');
        
        // Check if user is authorized
        $applicant = $session->applicant;
        
        // For public access (admin-generated links), allow access without user authentication
        if (!$applicant) {
            // This is a public test session created by admin
            // Check if current user has an applicant record and link it
            if (Auth::check()) {
                $userApplicant = \App\Models\Applicant::where('user_id', Auth::id())->first();
                if ($userApplicant) {
                    // Link this test session to the user's applicant record
                    $session->update([
                        'applicant_id' => $userApplicant->id,
                        'user_id' => Auth::id() // Also link with user account
                    ]);
                    $applicant = $userApplicant;
                } else {
                    // No applicant record, but user is logged in - link with user account
                    $session->update(['user_id' => Auth::id()]);
                }
            }
            // Allow access without user authentication
        } elseif (!$applicant->user_id || $applicant->user_id !== Auth::id()) {
            // This is a regular test session, require user authentication
            abort(403, 'Unauthorized access to test session.');
        }

        // Handle token issues
        if (!$session->isTokenValid($token)) {
            // Generate new token for completed session
            $newToken = $session->generateAccessToken();
            return redirect()->route('test.result', ['session' => $session, 'token' => $newToken]);
        }

        if (!$session->isCompleted()) {
            return redirect()->route('test.take', ['session' => $session, 'token' => $token]);
        }

        $session->load(['package.questions.options', 'answers.question.options']);
        
        // Calculate question numbers for display (1-based index)
        $questions = $session->package->questions()->orderBy('test_package_question.order')->get();
        $questionNumbers = [];
        foreach ($questions as $index => $question) {
            $questionNumbers[$question->id] = $index + 1;
        }
        
        return view('frontend.test.result', compact('session', 'questionNumbers'));
    }

    public function complete(Request $request, TestSession $session)
    {
        $token = $request->get('token');
        
        // Validate access token
        if (!$session->isTokenValid($token)) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired test session access token.'], 403);
        }

        // Check authorization
        $applicant = $session->applicant;
        
        // For public access (admin-generated links), allow access without user authentication
        if (!$applicant) {
            // This is a public test session created by admin
            // Check if current user has an applicant record and link it
            if (Auth::check()) {
                $userApplicant = \App\Models\Applicant::where('user_id', Auth::id())->first();
                if ($userApplicant) {
                    // Link this test session to the user's applicant record
                    $session->update(['applicant_id' => $userApplicant->id]);
                    $applicant = $userApplicant;
                }
            }
            // Allow access without user authentication
        } elseif (!$applicant->user_id || $applicant->user_id !== Auth::id()) {
            // This is a regular test session, require user authentication
            return response()->json(['success' => false, 'message' => 'Unauthorized access to test session.'], 403);
        }

        if ($session->isCompleted()) {
            $redirect = route('test.result', ['session' => $session, 'token' => $token]);
            \Log::info('Test already completed', [
                'session_id' => $session->id,
                'applicant_id' => $session->applicant_id,
                'is_screening_test' => $session->package->is_screening_test,
                'base_redirect' => $redirect
            ]);
            if ($session->applicant_id && $session->package->is_screening_test) {
                // flash a server-side flag so the result page can show the finalize modal
                session()->flash('show_finalize', true);
                $redirect .= '&finalize=1';
                \Log::info('Adding finalize=1 to redirect (already completed)', ['final_redirect' => $redirect]);
            }
            \Log::info('Returning complete response (already completed)', ['redirect' => $redirect]);
            return response()->json(['success' => true, 'redirect' => $redirect]);
        }

        $this->completeTest($session);
        
        $redirect = route('test.result', ['session' => $session, 'token' => $token]);
        \Log::info('Test just completed', [
            'session_id' => $session->id,
            'applicant_id' => $session->applicant_id,
            'is_screening_test' => $session->package->is_screening_test,
            'base_redirect' => $redirect
        ]);
        if ($session->applicant_id && $session->package->is_screening_test) {
            // flash a server-side flag so the result page can show the finalize modal
            session()->flash('show_finalize', true);
            $redirect .= '&finalize=1';
            \Log::info('Adding finalize=1 to redirect (just completed)', ['final_redirect' => $redirect]);
        }
        \Log::info('Returning complete response', ['redirect' => $redirect]);
        return response()->json(['success' => true, 'redirect' => $redirect]);
    }

    private function completeTest(TestSession $session)
    {
        // Check if test contains questions that require manual grading
        $hasEssayQuestions = $session->package->questions()
            ->where('question_type', 'essay')
            ->exists();
            
        $hasScaleQuestions = $session->package->questions()
            ->where('question_type', 'scale')
            ->exists();
            
        $hasForcedChoiceQuestions = $session->package->questions()
            ->where('question_type', 'forced_choice')
            ->exists();
            
        $hasVideoQuestions = $session->package->questions()
            ->where('question_type', 'video_record')
            ->exists();

        // Calculate score only for multiple choice questions
        $multipleChoiceScore = $session->answers()
            ->whereHas('question', function($query) {
                $query->where('question_type', 'multiple_choice');
            })
            ->sum('points_earned');

        $multipleChoiceMax = $session->package->questions()
            ->where('question_type', 'multiple_choice')
            ->sum('points');

        // If test contains questions that require manual grading, don't calculate overall score
        if ($hasEssayQuestions || $hasScaleQuestions || $hasForcedChoiceQuestions || $hasVideoQuestions) {
            $score = null;
            $isPassed = null;
            $notes = 'Test contains questions that require manual grading - no overall score calculated';
        } else {
            // Only calculate score if all questions are multiple choice
            if ($multipleChoiceMax > 0) {
                $score = round(($multipleChoiceScore / $multipleChoiceMax) * 100);
                $isPassed = $score >= $session->package->passing_score;
            } else {
                $score = 0;
                $isPassed = false;
            }
            $notes = null;
        }

        $session->update([
            'status' => 'completed',
            'completed_at' => Carbon::now(),
            'score' => $score,
            'is_passed' => $isPassed,
            'notes' => $notes
        ]);

        // Update applicant status based on test type
        if ($session->applicant_id) {
            $applicant = \App\Models\Applicant::find($session->applicant_id);
            if ($applicant) {
                // Only update status for screening tests, not for other tests in the flow
                if ($session->package->is_screening_test) {
                    // For screening tests, update to 'check' status
                $applicant->update(['status' => 'check']);
                $applicant->applications()->update([
                    'status' => 'check',
                    'test_completed_at' => Carbon::now(),
                    'test_score' => $score
                ]);
                } else {
                    // For other tests (psychology, etc.), only update test completion data
                    // Don't change the applicant status - let admin handle it via Next Step
                    $applicant->applications()->update([
                        'test_completed_at' => Carbon::now(),
                        'test_score' => $score
                    ]);
                }
            }
        }
        
        // Don't invalidate the token, keep it valid for result viewing
    }

    public function regenerateToken(Request $request, TestSession $session)
    {
        // Check if user is authorized
        if (Auth::check()) {
            $applicant = $session->applicant;
            if (!$applicant || $applicant->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to test session.');
            }
        } else {
            $token = $request->get('token');
            if (!$token || $session->access_token !== $token) {
                abort(403, 'Invalid or missing access token.');
            }
        }

        // Generate new token
        $newToken = $session->generateAccessToken();

        // Redirect to appropriate page based on session status
        if ($session->isCompleted()) {
            return redirect()->route('test.result', ['session' => $session, 'token' => $newToken]);
        } else {
            return redirect()->route('test.take', ['session' => $session, 'token' => $newToken]);
        }
    }
    
    public function uploadVideo(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimes:webm,mp4,avi,mov|max:50000' // 50MB max
        ]);
        
        try {
            // Generate unique filename
            $filename = 'testimonial_' . time() . '_' . uniqid() . '.webm';
            
            // Store video file on the public disk (will be available under /storage/test_videos/...)
            $path = $request->file('video')->storeAs('test_videos', $filename, 'public');

            // Get public URL
            $videoUrl = Storage::disk('public')->url($path);
            
            // Log successful upload for debugging
            \Log::info('Video uploaded successfully', [
                'filename' => $filename,
                'path' => $path,
                'url' => $videoUrl,
                'file_size' => $request->file('video')->getSize()
            ]);
            
            return response()->json([
                'success' => true,
                'video_url' => $videoUrl,
                'message' => 'Video uploaded successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Video upload error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload video: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show public package information page
     */
    public function publicPackage(TestPackage $package)
    {
        if (!$package->is_active) {
            abort(404, 'Test package is not available.');
        }

        // Load package with related data
        $package->load(['category', 'questions' => function($query) {
            $query->orderBy('test_package_question.order');
        }]);

        return view('frontend.test.public-package', compact('package'));
    }
}