<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\Interviewer;
use App\Models\JobVacancy;
use App\Models\Talent;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Barryvdh\DomPDF\Facade\Pdf;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        // Get all applications with their related data
        $query = Application::with(['user', 'applicant', 'jobVacancy', 'testSession.package', 'interviewer']);

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->status;
            // Special handling: if status is 'individual_interview', show both individual and group interview
            if ($status === 'individual_interview') {
                $query->whereIn('status', ['individual_interview', 'group_interview']);
            } else {
                $query->where('status', $status);
            }
        }

        // Filter by job vacancy
        if ($request->filled('job_vacancy_id')) {
            $query->where('job_vacancy_id', $request->job_vacancy_id);
        }


        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('applicant', function($applicantQuery) use ($search) {
                    $applicantQuery->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
                });
            });
        }

        $applications = $query->latest()->paginate(20);
        $jobVacancies = JobVacancy::where('status', 'active')->get();
        $interviewers = Interviewer::orderBy('name')->get();

        // Compute status counts (overall or per job if filter provided)
        $allStatuses = ['pending', 'sent', 'check', 'short_call', 'individual_interview', 'group_interview', 'test_psychology', 'ojt', 'final_interview', 'sent_offering_letter', 'rejected', 'rejected_by_applicant'];

        $countsQuery = Application::query();
        if ($request->filled('job_vacancy_id')) {
            $countsQuery->where('job_vacancy_id', $request->job_vacancy_id);
        }

        $existingCounts = $countsQuery->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Initialize all statuses with 0, then merge with existing counts
        $statusCounts = array_fill_keys($allStatuses, 0);
        $statusCounts = array_merge($statusCounts, $existingCounts);

        return view('admin.applicants.index', compact('applications', 'jobVacancies', 'statusCounts', 'interviewers'));
    }

    public function show(Applicant $applicant)
    {
        $applicant->load(['applications.jobVacancy', 'applications.testSession.answers.question', 'applications.testSession.package']);

        // For each application, compute multiple-choice only score dynamically (no DB writes)
        foreach ($applicant->applications as $application) {
            $session = $application->testSession;
            if ($session && $session->status === 'completed') {
                $multipleChoiceScore = $session->answers()
                    ->whereHas('question', function($query) {
                        $query->where('question_type', 'multiple_choice');
                    })
                    ->sum('points_earned');

                $multipleChoiceMax = $session->package->questions()
                    ->where('question_type', 'multiple_choice')
                    ->sum('points');

                $multipleChoiceScorePercentage = null;
                $multipleChoiceIsPassed = null;
                if ($multipleChoiceMax > 0) {
                    $multipleChoiceScorePercentage = round(($multipleChoiceScore / $multipleChoiceMax) * 100);
                    $multipleChoiceIsPassed = $multipleChoiceScorePercentage >= $session->package->passing_score;
                }

                // attach as dynamic attributes to testSession for view usage
                $session->multiple_choice_score = $multipleChoiceScorePercentage;
                $session->multiple_choice_is_passed = $multipleChoiceIsPassed;
                $session->multiple_choice_points = $multipleChoiceScore;
                $session->multiple_choice_max = $multipleChoiceMax;
            }
        }

        return view('admin.applicants.show', compact('applicant'));
    }

    public function updateStatus(Request $request, Applicant $applicant)
    {
        $request->validate([
            'status' => 'required|in:pending,sent,check,short_call,rejected',
            'notes' => 'nullable|string|max:1000'
        ]);

        $applicant->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        // Update all applications for this applicant
        $applicant->applications()->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    public function updateNotes(Request $request, Application $application)
    {
        try {
            $request->validate([
                'notes' => 'nullable|string|max:1000'
            ]);

            $application->update([
                'notes' => $request->notes ?? ''
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notes updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating notes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateInterviewer(Request $request, Application $application)
    {
        try {
            $request->validate([
                'interviewer_id' => 'nullable|integer|exists:interviewers,id',
                'interviewer_name' => 'nullable|string|max:255' // For backward compatibility
            ]);

            $interviewerId = $request->interviewer_id;
            $interviewerName = $request->interviewer_name;

            if ($interviewerId) {
                // Update with ID
                $interviewer = Interviewer::findOrFail($interviewerId);
                $application->update([
                    'interviewer_id' => $interviewer->id
                ]);
            } elseif ($interviewerName) {
                // Backward compatibility: handle by name
                $interviewerName = trim($interviewerName);
                $interviewer = Interviewer::where('name', $interviewerName)->first();

                if (!$interviewer) {
                    // Create new interviewer if doesn't exist
                    $interviewer = Interviewer::create([
                        'name' => $interviewerName
                    ]);
                }

                $application->update([
                    'interviewer_id' => $interviewer->id
                ]);
            } else {
                // Clear interviewer
                $application->update([
                    'interviewer_id' => null
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Interviewer updated successfully',
                'interviewer' => $application->interviewer ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating interviewer: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeInterviewer(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:interviewers,name',
                'email' => 'nullable|sometimes|email|max:255',
                'phone' => 'nullable|sometimes|string|max:20'
            ]);

            $interviewer = Interviewer::create([
                'name' => $request->name,
                'email' => $request->input('email') ?? null,
                'phone' => $request->input('phone') ?? null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Interviewer created successfully',
                'interviewer' => $interviewer
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating interviewer: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Error creating interviewer: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getApplicationNotes(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id'
        ]);

        $application = Application::findOrFail($request->application_id);
        \Log::info("Getting notes for application ID: {$application->id}, notes: {$application->notes}");

        return response()->json([
            'success' => true,
            'notes' => $application->notes
        ]);
    }

    public function sendTest(Request $request, Applicant $applicant)
    {
        try {
            // Get active screening test package
            $testPackage = \App\Models\TestPackage::where('is_screening_test', true)
                ->where('is_active', true)
                ->first();

            if (!$testPackage) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active test package found for screening'
                ], 400);
            }

            // Create test session
            $testSession = \App\Models\TestSession::create([
                'package_id' => $testPackage->id,
                'applicant_id' => $applicant->id,
                'user_id' => $applicant->user_id, // Add user_id to link with user account
                'access_token' => \Str::random(32),
                'status' => 'pending',
                'expires_at' => now()->addDay(),
            ]);

            // Update applicant status to 'sent'
            $applicant->update(['status' => 'sent']);
            $applicant->applications()->update(['status' => 'sent']);

            // Update application with test session
            $applicant->applications()->update([
                'test_session_id' => $testSession->id,
                'test_sent_at' => now()
            ]);

            // Generate test URL
            $testUrl = route('test.take', [
                'session' => $testSession,
                'token' => $testSession->access_token
            ]);

            // Send WhatsApp invitation
            $whatsappService = new WhatsAppService();
            $result = $whatsappService->sendTestInvitation($applicant, $testUrl);

            return response()->json([
                'success' => true,
                'message' => 'Test invitation sent successfully via WhatsApp',
                'test_url' => $testUrl
            ]);

        } catch (\Exception $e) {
            \Log::error('Send test invitation error:', [
                'applicant_id' => $applicant->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error sending test invitation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function nextStep(Request $request, Applicant $applicant)
    {
        // Debug incoming request to trace WhatsApp template flow
        \Log::debug('nextStep called', [
            'applicant_id' => $applicant->id,
            'request' => $request->all()
        ]);

        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'notes' => 'nullable|string|max:1000',
            'template_id' => 'required|exists:whatsapp_templates,id'
        ]);

        $template = \App\Models\WhatsAppTemplate::findOrFail($request->template_id);
        \Log::debug('nextStep template found', ['template_id' => $template->id, 'type' => $template->type]);
        
        // Get specific application
        $application = Application::findOrFail($request->application_id);
        
        // Verify application belongs to this applicant
        if ($application->applicant_id !== $applicant->id) {
            return response()->json([
                'success' => false,
                'message' => 'Application does not belong to this applicant.'
            ], 400);
        }

        // Determine next status based on current application status
        $nextStatus = $this->getNextStatus($application->status);
        
        if (!$nextStatus) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid current status for next step.'
            ], 400);
        }

        // Validate template type based on current status
        $expectedTemplateType = $this->getExpectedTemplateType(currentStatus: $application->status);
        if ($template->type !== $expectedTemplateType) {
            return response()->json([
                'success' => false,
                'message' => "Invalid template type. Expected: {$expectedTemplateType}, Got: {$template->type}"
            ], 400);
        }

        // Update application status
        // IMPORTANT: For Next Step modal we want notes to be REPLACED, not appended.
        // Frontend already sends the full, final notes (including any auto-generated schedule text),
        // so we just persist exactly what comes from the request.
        $application->update([
            'status' => $nextStatus,
            'notes' => $request->notes ?? ''
        ]);

        // Update applicant status to match the latest application status
        $latestApplication = $applicant->applications()->latest()->first();
        if ($latestApplication) {
            $applicant->update(['status' => $latestApplication->status]);
        }

        // Generate WhatsApp URL with template
        $whatsappUrl = $this->generateWhatsAppUrl($template, $applicant, $application);

        return response()->json([
            'success' => true,
            'message' => 'Moved to next step. Please send WhatsApp invitation.',
            'whatsapp_url' => $whatsappUrl
        ]);
    }

    private function getNextStatus($currentStatus)
    {
        return match($currentStatus) {
            'check' => 'short_call',
            'short_call' => 'group_interview',
            'group_interview' => 'test_psychology',
            'individual_interview' => 'test_psychology',
            'test_psychology' => 'ojt',
            'ojt' => 'final_interview',
            'final_interview' => 'sent_offering_letter',
            default => null
        };
    }

    private function getExpectedTemplateType($currentStatus)
    {
        return match($currentStatus) {
            'check' => 'short_call_invitation',
            'short_call' => 'group_interview_invitation',
            'group_interview' => 'test_psychology_invitation',
            'individual_interview' => 'test_psychology_invitation',
            'test_psychology' => 'ojt_invitation',
            'ojt' => 'final_interview_invitation',
            'final_interview' => 'offering_letter_invitation',
            default => null
        };
    }

    public function downloadCv(Applicant $applicant)
    {
        if (!$applicant->cv_path || !Storage::disk('public')->exists($applicant->cv_path)) {
            abort(404, 'CV not found');
        }

        return Storage::disk('public')->download($applicant->cv_path, $applicant->name . '_CV.pdf');
    }

    public function viewPhoto(Applicant $applicant)
    {
        if (!$applicant->photo_path || !Storage::disk('public')->exists($applicant->photo_path)) {
            abort(404, 'Photo not found');
        }

        return response()->file(Storage::disk('public')->path($applicant->photo_path));
    }

    public function viewCv(Applicant $applicant)
    {
        if (!$applicant->cv_path || !Storage::disk('public')->exists($applicant->cv_path)) {
            abort(404, 'CV not found');
        }

        return response()->file(Storage::disk('public')->path($applicant->cv_path));
    }

    public function getWhatsAppData(Applicant $applicant)
    {
        try {
            // Get the latest application for this applicant
            $application = $applicant->applications()->latest()->first();
            
            if (!$application) {
                return response()->json([
                    'success' => false,
                    'message' => 'No application found for this applicant'
                ], 404);
            }

            // Load job vacancy and test session data
            $application->load('jobVacancy', 'testSession');

            // Get templates based on current status
            $templateType = $this->getTemplateTypeForStatus($applicant->status);
            $templates = \App\Models\WhatsAppTemplate::where('type', $templateType)->get();

            // Get existing talent data if available
            $talent = $applicant->talent()->latest()->first();
            $talentData = null;
            if ($talent) {
                $talentData = [
                    'level_potential' => $talent->level_potential,
                    'talent_potential' => $talent->talent_potential,
                    'position_potential' => $talent->potential_position,
                    'communication' => $talent->communication,
                    'attitude' => $talent->attitude_level,
                    'initiative' => $talent->initiative,
                    'leadership' => $talent->leadership,
                    'notes' => $talent->notes
                ];
                \Log::info('Talent data found for applicant ' . $applicant->id . ':', $talentData);
            } else {
                \Log::info('No talent data found for applicant ' . $applicant->id);
            }

            // Prepare test session data if available
            $testSessionData = null;
            if ($application->testSession) {
                $testSessionData = [
                    'id' => $application->testSession->id,
                    'access_token' => $application->testSession->access_token,
                    'status' => $application->testSession->status
                ];
            }

            return response()->json([
                'success' => true,
                'applicant' => [
                    'id' => $applicant->id,
                    'name' => $applicant->name,
                    'whatsapp' => $applicant->whatsapp
                ],
                'application' => [
                    'id' => $application->id,
                    'status' => $application->status,
                    'testSession' => $testSessionData
                ],
                'job' => [
                    'id' => $application->jobVacancy->id,
                    'position' => $application->jobVacancy->position,
                    'company_name' => $application->jobVacancy->company_name
                ],
                'talent' => $talentData,
                'templates' => $templates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting applicant data: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getTemplateTypeForStatus($status)
    {
        switch ($status) {
            case 'check':
                return 'short_call_invitation';
            case 'short_call':
                return 'group_interview_invitation';
            case 'group_interview':
                return 'test_psychology_invitation';
            case 'test_psychology':
                return 'ojt_invitation';
            case 'ojt':
                return 'final_interview_invitation';
            default:
                return 'short_call_invitation';
        }
    }

    private function sendShortCallInvitation(Applicant $applicant)
    {
        $whatsappService = new WhatsAppService();
        return $whatsappService->sendShortCallInvitation($applicant);
    }

    private function generateWhatsAppUrl($template, $applicant, $application, $reason = null)
    {
        $data = [
            'NAME' => $applicant->name,
            'POSITION' => $application->jobVacancy->position,
            'COMPANY' => $application->jobVacancy->company_name,
            'DATE' => now()->format('d M Y'),
            'REASON' => $reason ?? 'Not meeting requirements'
        ];
        return $template->generateWhatsAppUrl($applicant->whatsapp, $data);
    }

    public function groupInterview(Request $request, Applicant $applicant)
    {
        // Debug: Log all request data
        \Log::info('Group Interview - Raw Request Data:', [
            'all' => $request->all(),
            'headers' => $request->headers->all(),
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'name' => 'required|string|max:255',
            'level_potential' => 'nullable|string|max:255',
            'talent_potential' => 'nullable|string|max:255',
            'position_potential' => 'nullable|string|max:255',
            'communication' => 'nullable|integer|min:1|max:5',
            'attitude' => 'nullable|integer|min:1|max:5',
            'initiative' => 'nullable|integer|min:1|max:5',
            'leadership' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string'
        ]);

        try {
            // Debug: Log the request data
            \Log::info('Group Interview Request Data:', $request->all());
            
            // Get specific application for status update
            $application = Application::findOrFail($request->application_id);
            
            // Verify application belongs to this applicant
            if ($application->applicant_id !== $applicant->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Application does not belong to this applicant.'
                ], 400);
            }
            
            $city = $application->jobVacancy ? $application->jobVacancy->location : 'Unknown';
            
            // Update application status to group_interview and save notes
            $application->update([
                'status' => 'group_interview',
                'notes' => $request->notes ? ($application->notes ? $application->notes . "\n\n" . $request->notes : $request->notes) : $application->notes
            ]);
            
            // Update applicant status to match the latest application status
            $latestApplication = $applicant->applications()->latest()->first();
            if ($latestApplication) {
                $applicant->update(['status' => $latestApplication->status]);
            }
            
            // Debug: Log the status update
            \Log::info('Status Update Debug:', [
                'applicant_id' => $applicant->id,
                'application_id' => $application->id,
                'old_status' => $application->getOriginal('status'),
                'new_status' => 'group_interview',
                'updated_application_status' => $application->fresh()->status,
                'updated_applicant_status' => $applicant->fresh()->status
            ]);

            // Create talent record with flexible data
            $talentData = [
                'name' => $request->name,
                'city' => $city,
                'attitude_level' => $request->attitude ?: null,
                'level_potential' => $request->level_potential,
                'potential_position' => $request->position_potential,
                'communication' => $request->communication,
                'talent_potential' => $request->talent_potential,
                'initiative' => $request->initiative,
                'leadership' => $request->leadership,
                'notes' => $request->notes,
                'applicant_id' => $applicant->id,
                'user_id' => $applicant->user_id
            ];

            // Remove null values to keep database clean
            $talentData = array_filter($talentData, function($value) {
                return $value !== null && $value !== '';
            });

            // Debug: Log the final talent data before saving
            \Log::info('Final Talent Data Before Save:', $talentData);

            // Update existing talent or create new one
            $talent = $applicant->talent()->updateOrCreate(
                ['applicant_id' => $applicant->id],
                $talentData
            );
            
            // Debug: Log the saved talent data
            \Log::info('Saved Talent Data:', $talent->toArray());

            // Log the action
            \Log::info("Applicant {$applicant->name} moved to group interview and saved to talent database");

            return response()->json([
                'success' => true,
                'message' => 'Applicant moved to group interview and saved to talent database'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in groupInterview: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing group interview: ' . $e->getMessage()
            ], 500);
        }
    }

    public function individualInterview(Request $request, Applicant $applicant)
    {
        // Debug: Log all request data
        \Log::info('Individual Interview - Raw Request Data:', [
            'all' => $request->all(),
            'headers' => $request->headers->all(),
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'name' => 'required|string|max:255',
            'level_potential' => 'nullable|string|max:255',
            'talent_potential' => 'nullable|string|max:255',
            'position_potential' => 'nullable|string|max:255',
            'communication' => 'nullable|integer|min:1|max:5',
            'attitude' => 'nullable|integer|min:1|max:5',
            'initiative' => 'nullable|integer|min:1|max:5',
            'leadership' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string'
        ]);

        try {
            // Debug: Log the request data
            \Log::info('Individual Interview Request Data:', $request->all());
            
            // Get specific application for status update
            $application = Application::findOrFail($request->application_id);
            
            // Verify application belongs to this applicant
            if ($application->applicant_id !== $applicant->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Application does not belong to this applicant.'
                ], 400);
            }
            
            $city = $application->jobVacancy ? $application->jobVacancy->location : 'Unknown';
            
            // Update application status to individual_interview and save notes
            $application->update([
                'status' => 'individual_interview',
                'notes' => $request->notes ? ($application->notes ? $application->notes . "\n\n" . $request->notes : $request->notes) : $application->notes
            ]);
            
            // Update applicant status to match the latest application status
            $latestApplication = $applicant->applications()->latest()->first();
            if ($latestApplication) {
                $applicant->update(['status' => $latestApplication->status]);
            }
            
            // Debug: Log the status update
            \Log::info('Status Update Debug:', [
                'applicant_id' => $applicant->id,
                'application_id' => $application->id,
                'old_status' => $application->getOriginal('status'),
                'new_status' => 'individual_interview',
                'updated_application_status' => $application->fresh()->status,
                'updated_applicant_status' => $applicant->fresh()->status
            ]);

            // Create talent record with flexible data
            $talentData = [
                'name' => $request->name,
                'city' => $city,
                'attitude_level' => $request->attitude ?: null,
                'level_potential' => $request->level_potential,
                'potential_position' => $request->position_potential,
                'communication' => $request->communication,
                'talent_potential' => $request->talent_potential,
                'initiative' => $request->initiative,
                'leadership' => $request->leadership,
                'notes' => $request->notes,
                'applicant_id' => $applicant->id,
                'user_id' => $applicant->user_id
            ];

            // Remove null values to keep database clean
            $talentData = array_filter($talentData, function($value) {
                return $value !== null && $value !== '';
            });

            // Debug: Log the final talent data before saving
            \Log::info('Final Talent Data Before Save:', $talentData);

            // Update existing talent or create new one
            $talent = $applicant->talent()->updateOrCreate(
                ['applicant_id' => $applicant->id],
                $talentData
            );
            
            // Debug: Log the saved talent data
            \Log::info('Saved Talent Data:', $talent->toArray());

            // Log the action
            \Log::info("Applicant {$applicant->name} moved to individual interview and saved to talent database");

            return response()->json([
                'success' => true,
                'message' => 'Applicant moved to individual interview and saved to talent database'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in individualInterview: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing individual interview: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rejectSaveTalent(Request $request, Applicant $applicant)
    {
        $request->validate([
            'application_id' => 'nullable|exists:applications,id',
            'name' => 'required|string|max:255',
            'level_potential' => 'nullable|string|max:255',
            'talent_potential' => 'nullable|string|max:255',
            'position_potential' => 'nullable|string|max:255',
            'communication' => 'nullable|integer|min:1|max:5',
            'attitude' => 'nullable|integer|min:1|max:5',
            'initiative' => 'nullable|integer|min:1|max:5',
            'leadership' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string'
        ]);

        try {
            // Debug: Log all request data
            \Log::info('RejectSaveTalent Request Data:', [
                'applicant_id' => $applicant->id,
                'application_id' => $request->application_id,
                'all_request_data' => $request->all()
            ]);
            
            // Get the application - use provided application_id or latest
            if ($request->application_id) {
                $application = $applicant->applications()->find($request->application_id);
                \Log::info('Found application by ID:', ['application_id' => $request->application_id, 'application' => $application ? $application->toArray() : 'null']);
            } else {
                $application = $applicant->applications()->latest()->first();
                \Log::info('Found latest application:', ['application' => $application ? $application->toArray() : 'null']);
            }
            
            $city = $application && $application->jobVacancy ? $application->jobVacancy->location : 'Unknown';
            
            // Get current status as last stage
            $lastStage = $application ? $application->status : 'unknown';
            
            \Log::info('Before update - Application status:', [
                'application_id' => $application ? $application->id : 'null',
                'current_status' => $lastStage,
                'applicant_id' => $applicant->id
            ]);
            
            // Update only the specific application to rejected
            if ($application) {
                $updateResult = $application->update([
                    'status' => 'rejected',
                    'last_stage' => $lastStage
                ]);
                
                \Log::info('Application update result:', [
                    'application_id' => $application->id,
                    'update_success' => $updateResult,
                    'new_status' => $application->fresh()->status
                ]);
            } else {
                \Log::error('No application found to update');
            }
            
            // Update applicant status to rejected
            $applicantUpdateResult = $applicant->update(['status' => 'rejected']);
            \Log::info('Applicant update result:', [
                'applicant_id' => $applicant->id,
                'update_success' => $applicantUpdateResult,
                'new_status' => $applicant->fresh()->status
            ]);

            // Normalize phone number for talent
            $normalizedPhone = null;
            if (!empty($applicant->whatsapp)) {
                $cleanPhone = preg_replace('/[^0-9]/', '', $applicant->whatsapp);
                if (substr($cleanPhone, 0, 1) === '0') {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                } elseif (substr($cleanPhone, 0, 2) !== '62') {
                    $cleanPhone = '62' . $cleanPhone;
                }
                $normalizedPhone = $cleanPhone;
            }

            // Create talent record with flexible data
            $talentData = [
                'name' => $request->name,
                'city' => $city,
                'attitude_level' => $request->attitude ?: null,
                'level_potential' => $request->level_potential,
                'potential_position' => $request->position_potential,
                'communication' => $request->communication,
                'talent_potential' => $request->talent_potential,
                'initiative' => $request->initiative,
                'leadership' => $request->leadership,
                'notes' => $request->notes,
                'applicant_id' => $applicant->id,
                'user_id' => $applicant->user_id,
                'whatsapp' => $normalizedPhone,
            ];

            // Remove null values to keep database clean
            $talentData = array_filter($talentData, function($value) {
                return $value !== null && $value !== '';
            });

            // Check if talent already exists for this user
            $existingTalent = Talent::where('user_id', $applicant->user_id)->first();
            
            if ($existingTalent) {
                // Update existing talent instead of creating new one
                \Log::info('Talent already exists for user ' . $applicant->user_id . ', updating existing talent');
                
                // Update the existing talent with new data
                $existingTalent->update($talentData);
                $talent = $existingTalent;
                
                \Log::info('Talent updated successfully with ID: ' . $talent->id);
            } else {
                // Create new talent record
                \Log::info('Creating new talent with data:', $talentData);
                
                try {
                    $talent = $applicant->talent()->create($talentData);
                    \Log::info('Talent created successfully with ID: ' . $talent->id);
                } catch (\Exception $e) {
                    \Log::error('Error creating talent: ' . $e->getMessage());
                    \Log::error('Talent data that failed:', $talentData);
                    throw $e;
                }
            }

            // Log the action
            \Log::info("Applicant {$applicant->name} rejected but saved to talent database at stage: {$lastStage}");

            return response()->json([
                'success' => true,
                'message' => 'Applicant rejected and saved to talent database'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in rejectSaveTalent: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing reject save talent: ' . $e->getMessage()
            ], 500);
        }
    }


    // Test Psychology
    public function testPsychology(Request $request, Applicant $applicant)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'name' => 'required|string|max:255',
            'level_potential' => 'nullable|string|max:255',
            'talent_potential' => 'nullable|string|max:255',
            'position_potential' => 'nullable|string|max:255',
            'communication' => 'nullable|integer|min:1|max:5',
            'attitude' => 'nullable|integer|min:1|max:5',
            'initiative' => 'nullable|integer|min:1|max:5',
            'leadership' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string'
        ]);

        try {
            // Get specific application for status update
            $application = Application::findOrFail($request->application_id);
            
            // Verify application belongs to this applicant
            if ($application->applicant_id !== $applicant->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Application does not belong to this applicant.'
                ], 400);
            }
            
            $city = $application->jobVacancy ? $application->jobVacancy->location : 'Unknown';
            
            // Update application status to test_psychology and save notes
            $application->update([
                'status' => 'test_psychology',
                'notes' => $request->notes ? ($application->notes ? $application->notes . "\n\n" . $request->notes : $request->notes) : $application->notes
            ]);
            
            // Update applicant status to match the latest application status
            $latestApplication = $applicant->applications()->latest()->first();
            if ($latestApplication) {
                $applicant->update(['status' => $latestApplication->status]);
            }

            // Create or update talent record
            $talentData = [
                'name' => $request->name,
                'city' => $city,
                'attitude_level' => $request->attitude ?: null,
                'level_potential' => $request->level_potential,
                'potential_position' => $request->position_potential,
                'communication' => $request->communication,
                'talent_potential' => $request->talent_potential,
                'initiative' => $request->initiative,
                'leadership' => $request->leadership,
                'notes' => $request->notes,
                'applicant_id' => $applicant->id,
                'user_id' => $applicant->user_id
            ];

            // Remove null values to keep database clean
            $talentData = array_filter($talentData, function($value) {
                return $value !== null && $value !== '';
            });

            // Update existing talent or create new one
            $talent = $applicant->talent()->updateOrCreate(
                ['applicant_id' => $applicant->id],
                $talentData
            );

            \Log::info("Applicant {$applicant->name} moved to test psychology and updated talent database");

            return response()->json([
                'success' => true,
                'message' => 'Applicant moved to test psychology and updated talent database'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in testPsychology: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing test psychology: ' . $e->getMessage()
            ], 500);
        }
    }

    // OJT (On Job Training)
    public function ojt(Request $request, Applicant $applicant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level_potential' => 'nullable|string|max:255',
            'talent_potential' => 'nullable|string|max:255',
            'position_potential' => 'nullable|string|max:255',
            'communication' => 'nullable|integer|min:1|max:5',
            'attitude' => 'nullable|integer|min:1|max:5',
            'initiative' => 'nullable|integer|min:1|max:5',
            'leadership' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string'
        ]);

        try {
            // Get the latest application for this applicant to get city
            $application = $applicant->applications()->latest()->first();
            
            if (!$application) {
                return response()->json([
                    'success' => false,
                    'message' => 'No application found for this applicant.'
                ], 400);
            }
            
            $city = $application->jobVacancy ? $application->jobVacancy->location : 'Unknown';
            
            // Update application notes
            $application->update([
                'notes' => $request->notes ?? ''
            ]);

            // Create or update talent record
            $talentData = [
                'name' => $request->name,
                'city' => $city,
                'attitude_level' => $request->attitude ?: null,
                'level_potential' => $request->level_potential,
                'potential_position' => $request->position_potential,
                'communication' => $request->communication,
                'talent_potential' => $request->talent_potential,
                'initiative' => $request->initiative,
                'leadership' => $request->leadership,
                'notes' => $request->notes,
                'applicant_id' => $applicant->id,
                'user_id' => $applicant->user_id
            ];

            // Remove null values to keep database clean
            $talentData = array_filter($talentData, function($value) {
                return $value !== null && $value !== '';
            });

            // Update existing talent or create new one
            $talent = $applicant->talent()->updateOrCreate(
                ['applicant_id' => $applicant->id],
                $talentData
            );

            \Log::info("Applicant {$applicant->name} moved to OJT and updated talent database");

            return response()->json([
                'success' => true,
                'message' => 'Applicant moved to OJT and updated talent database'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in ojt: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing OJT: ' . $e->getMessage()
            ], 500);
        }
    }

    // Final Interview
    public function finalInterview(Request $request, Applicant $applicant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level_potential' => 'nullable|string|max:255',
            'talent_potential' => 'nullable|string|max:255',
            'position_potential' => 'nullable|string|max:255',
            'communication' => 'nullable|integer|min:1|max:5',
            'attitude' => 'nullable|integer|min:1|max:5',
            'initiative' => 'nullable|integer|min:1|max:5',
            'leadership' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string'
        ]);

        try {
            // Get the latest application for this applicant to get city
            $application = $applicant->applications()->latest()->first();
            
            if (!$application) {
                return response()->json([
                    'success' => false,
                    'message' => 'No application found for this applicant.'
                ], 400);
            }
            
            $city = $application->jobVacancy ? $application->jobVacancy->location : 'Unknown';
            
            // Update application notes
            $application->update([
                'notes' => $request->notes ?? ''
            ]);

            // Create or update talent record
            $talentData = [
                'name' => $request->name,
                'city' => $city,
                'attitude_level' => $request->attitude ?: null,
                'level_potential' => $request->level_potential,
                'potential_position' => $request->position_potential,
                'communication' => $request->communication,
                'talent_potential' => $request->talent_potential,
                'initiative' => $request->initiative,
                'leadership' => $request->leadership,
                'notes' => $request->notes,
                'applicant_id' => $applicant->id,
                'user_id' => $applicant->user_id
            ];

            // Remove null values to keep database clean
            $talentData = array_filter($talentData, function($value) {
                return $value !== null && $value !== '';
            });

            // Update existing talent or create new one
            $talent = $applicant->talent()->updateOrCreate(
                ['applicant_id' => $applicant->id],
                $talentData
            );

            \Log::info("Applicant {$applicant->name} moved to final interview and updated talent database");

            return response()->json([
                'success' => true,
                'message' => 'Applicant moved to final interview and updated talent database'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in finalInterview: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing final interview: ' . $e->getMessage()
            ], 500);
        }
    }

    // Send Offering Letter
    public function sendOfferingLetter(Request $request, Applicant $applicant)
    {
        try {
            // Update applicant status to sent_offering_letter
            $applicant->update(['status' => 'sent_offering_letter']);

            \Log::info("Offering letter sent to applicant {$applicant->name}");

            return response()->json([
                'success' => true,
                'message' => 'Offering letter sent successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in sendOfferingLetter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error sending offering letter: ' . $e->getMessage()
            ], 500);
        }
    }

    // Accept Applicant
    public function acceptApplicant(Request $request, Applicant $applicant)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id'
        ]);

        try {
            // Get specific application
            $application = Application::findOrFail($request->application_id);
            
            // Verify application belongs to this applicant
            if ($application->applicant_id !== $applicant->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Application does not belong to this applicant.'
                ], 400);
            }
            
            // Update specific application to onboard
            $application->update(['status' => 'onboard']);
            
            // Update applicant status to match the latest application status
            $latestApplication = $applicant->applications()->latest()->first();
            if ($latestApplication) {
                $applicant->update(['status' => $latestApplication->status]);
            }

            \Log::info("Applicant {$applicant->name} accepted and onboarded for application {$application->id}");

            return response()->json([
                'success' => true,
                'message' => 'Applicant accepted and onboarded successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in acceptApplicant: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error accepting applicant: ' . $e->getMessage()
            ], 500);
        }
    }

    // Reject by Applicant
    public function rejectByApplicant(Request $request, Applicant $applicant)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id'
        ]);

        try {
            // Get specific application
            $application = Application::findOrFail($request->application_id);
            
            // Verify application belongs to this applicant
            if ($application->applicant_id !== $applicant->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Application does not belong to this applicant.'
                ], 400);
            }
            
            // Update specific application to rejected_by_applicant
            $application->update(['status' => 'rejected_by_applicant']);
            
            // Update applicant status to match the latest application status
            $latestApplication = $applicant->applications()->latest()->first();
            if ($latestApplication) {
                $applicant->update(['status' => $latestApplication->status]);
            }

            \Log::info("Applicant {$applicant->name} rejected by applicant for application {$application->id}");

            return response()->json([
                'success' => true,
                'message' => 'Applicant rejected by applicant'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in rejectByApplicant: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting applicant: ' . $e->getMessage()
            ], 500);
        }
    }

    // Resend Offering Letter
    public function resendOfferingLetter(Request $request, Applicant $applicant)
    {
        try {
            // Update applicant status back to sent_offering_letter
            $applicant->update(['status' => 'sent_offering_letter']);

            \Log::info("Offering letter resent to applicant {$applicant->name}");

            return response()->json([
                'success' => true,
                'message' => 'Offering letter resent successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in resendOfferingLetter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error resending offering letter: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update reject methods to track last stage
    public function reject(Request $request, Applicant $applicant)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'template_id' => 'required|exists:whatsapp_templates,id',
            'reason' => 'nullable|string|max:1000'
        ]);

        try {
            // Get specific application
            $application = Application::findOrFail($request->application_id);
            
            // Verify application belongs to this applicant
            if ($application->applicant_id !== $applicant->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Application does not belong to this applicant'
                ], 400);
            }

            // Get current status as last stage
            $lastStage = $application->status;
            
            // Update application status to rejected and save last stage
            $application->update([
                'status' => 'rejected',
                'last_stage' => $lastStage
            ]);
            
            // Update applicant status to match the latest application status
            $latestApplication = $applicant->applications()->latest()->first();
            if ($latestApplication) {
                $applicant->update(['status' => $latestApplication->status]);
            }

            \Log::info("Application {$application->id} rejected at stage: {$lastStage}");

            // If a template_id is provided, generate WhatsApp URL using the template
            $whatsappUrl = null;
            if ($request->filled('template_id')) {
                try {
                    $template = \App\Models\WhatsAppTemplate::findOrFail($request->template_id);
                    $data = [
                        'NAME' => $applicant->name,
                        'POSITION' => $application->jobVacancy->position ?? '',
                        'COMPANY' => $application->jobVacancy->company_name ?? '',
                        'DATE' => now()->format('d M Y'),
                        'REASON' => $request->reason ?? ''
                    ];
                    $whatsappUrl = $template->generateWhatsAppUrl($applicant->whatsapp, $data);
                    \Log::debug('reject generated whatsapp_url', ['url' => $whatsappUrl, 'template_id' => $template->id]);
                } catch (\Exception $e) {
                    \Log::error('Error generating whatsapp url on reject: ' . $e->getMessage());
                }
            }

            $response = [
                'success' => true,
                'message' => 'Application rejected successfully'
            ];

            if ($whatsappUrl) {
                $response['whatsapp_url'] = $whatsappUrl;
            }

            return response()->json($response);

        } catch (\Exception $e) {
            \Log::error('Error in reject: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting application: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            // Get filter parameters
            $status = $request->get('status');
            $jobVacancyId = $request->get('job_vacancy_id');
            $search = $request->get('search');

            // Build query with filters
            $query = Application::with(['user', 'applicant', 'jobVacancy', 'interviewer']);

            if ($request->filled('status')) {
                $query->where('status', $status);
            }

            if ($request->filled('job_vacancy_id')) {
                $query->where('job_vacancy_id', $jobVacancyId);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('user', function($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('applicant', function($applicantQuery) use ($search) {
                        $applicantQuery->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                    });
                });
            }

            $applications = $query->orderBy('created_at', 'desc')->get();

            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set title
            $sheet->setTitle('Applicants Export');

            // Set headers
            $headers = [
                'ID',
                'Name',
                'Email',
                'WhatsApp',
                'Position Applied',
                'Status',
                'Applied Date',
                'CV Link',
                'Photo Link',
                'Interviewer',
                'Notes',
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
            foreach ($applications as $application) {
                $col = 1;
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $application->id);
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $application->user->name ?? $application->applicant->name ?? 'N/A');
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $application->user->email ?? $application->applicant->email ?? 'N/A');
                
                // Format WhatsApp as text to prevent scientific notation
                $whatsapp = $application->applicant->whatsapp ?? 'N/A';
                if ($whatsapp !== 'N/A') {
                    // Normalize phone number
                    $cleanPhone = preg_replace('/[^0-9]/', '', $whatsapp);
                    if (substr($cleanPhone, 0, 1) === '0') {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    } elseif (substr($cleanPhone, 0, 2) !== '62') {
                        $cleanPhone = '62' . $cleanPhone;
                    }
                    // Set as text to prevent Excel from converting to number
                    $sheet->setCellValueExplicit(
                        \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row,
                        $cleanPhone,
                        DataType::TYPE_STRING
                    );
                } else {
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, 'N/A');
                }
                
                // Position with location
                $positionText = $application->jobVacancy->position ?? 'N/A';
                if ($application->jobVacancy->location) {
                    $positionText .= ' - ' . $application->jobVacancy->location;
                }
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $positionText);
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, ucfirst(str_replace('_', ' ', $application->status)));
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $application->created_at->format('Y-m-d H:i:s'));
                
                // CV Link - Generate absolute URL
                $cvLink = 'N/A';
                if ($application->applicant->cv_path && Storage::disk('public')->exists($application->applicant->cv_path)) {
                    $cvLink = url(route('admin.applicants.view-cv', $application->applicant, false));
                }
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $cvLink);
                
                // Photo Link - Generate absolute URL
                $photoLink = 'N/A';
                if ($application->applicant->photo_path && Storage::disk('public')->exists($application->applicant->photo_path)) {
                    $photoLink = url(route('admin.applicants.view-photo', $application->applicant, false));
                }
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $photoLink);

                // Interviewer name
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $application->interviewer->name ?? 'N/A');

                // Notes (limited length for readability)
                $notes = $application->notes ? \Illuminate\Support\Str::limit($application->notes, 200) : '';
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col++) . $row, $notes);
                
                $row++;
            }

            // Auto-size columns
            $totalColumns = count($headers);
            for ($col = 1; $col <= $totalColumns; $col++) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
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
            $filename = 'applicants-export';
            if ($status) {
                $filename .= '-' . $status;
            }
            if ($jobVacancyId) {
                $job = JobVacancy::find($jobVacancyId);
                $filename .= '-' . strtolower(str_replace(' ', '-', $job->position ?? 'job'));
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
            $jobVacancyId = $request->get('job_vacancy_id');
            $search = $request->get('search');

            // Build query with filters
            $query = Application::with(['user', 'applicant', 'jobVacancy']);

            if ($request->filled('status')) {
                $query->where('status', $status);
            }

            if ($request->filled('job_vacancy_id')) {
                $query->where('job_vacancy_id', $jobVacancyId);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('user', function($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('applicant', function($applicantQuery) use ($search) {
                        $applicantQuery->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                    });
                });
            }

            $applications = $query->orderBy('created_at', 'desc')->get();

            // Get filter info for display
            $filterInfo = [];
            if ($status) {
                $filterInfo[] = 'Status: ' . ucfirst(str_replace('_', ' ', $status));
            }
            if ($jobVacancyId) {
                $job = JobVacancy::find($jobVacancyId);
                $filterInfo[] = 'Job: ' . ($job->position ?? 'N/A');
            }
            if ($search) {
                $filterInfo[] = 'Search: ' . $search;
            }

            $data = [
                'applications' => $applications,
                'filterInfo' => $filterInfo,
                'exportDate' => now()->format('d M Y H:i:s')
            ];

            $pdf = Pdf::loadView('admin.applicants.export-pdf', $data);
            $pdf->setPaper('A4', 'landscape');

            // Create filename with filters
            $filename = 'applicants-export';
            if ($status) {
                $filename .= '-' . $status;
            }
            if ($jobVacancyId) {
                $job = JobVacancy::find($jobVacancyId);
                $filename .= '-' . strtolower(str_replace(' ', '-', $job->position ?? 'job'));
            }
            $filename .= '-' . date('Y-m-d-H-i-s') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error exporting PDF: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, Applicant $applicant)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id'
        ]);

        try {
            // Get specific application
            $application = Application::findOrFail($request->application_id);
            
            // Verify application belongs to this applicant
            if ($application->applicant_id !== $applicant->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Application does not belong to this applicant'
                ], 400);
            }

            // Delete related test session if exists
            if ($application->test_session_id) {
                $testSession = \App\Models\TestSession::find($application->test_session_id);
                if ($testSession) {
                    $testSession->delete();
                    \Log::info("Test session {$application->test_session_id} deleted for application {$application->id}");
                }
            }

            // Delete the application
            $application->delete();

            \Log::info("Application {$application->id} deleted successfully");

            return response()->json([
                'success' => true,
                'message' => 'Application deleted successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error deleting application: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting application: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array|min:1',
            'application_ids.*' => 'exists:applications,id'
        ]);

        try {
            $applicationIds = $request->application_ids;
            $deletedCount = 0;

            foreach ($applicationIds as $applicationId) {
                $application = Application::findOrFail($applicationId);
                
                // Delete related test session if exists
                if ($application->test_session_id) {
                    $testSession = \App\Models\TestSession::find($application->test_session_id);
                    if ($testSession) {
                        $testSession->delete();
                        \Log::info("Test session {$application->test_session_id} deleted for application {$application->id}");
                    }
                }

                // Delete the application
                $application->delete();
                $deletedCount++;
            }

            \Log::info("Bulk deleted {$deletedCount} applications");

            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} applications deleted successfully"
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in bulk delete: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting applications: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkReject(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array|min:1',
            'application_ids.*' => 'exists:applications,id',
            'reason' => 'nullable|string|max:1000'
        ]);

        try {
            $applicationIds = $request->application_ids;
            $reason = $request->reason;
            $rejectedCount = 0;

            foreach ($applicationIds as $applicationId) {
                $application = Application::findOrFail($applicationId);
                $applicant = $application->applicant;
                
                if (!$applicant) {
                    continue;
                }

                // Get current status as last stage
                $lastStage = $application->status;
                
                // Update application status to rejected and save last stage
                $application->update([
                    'status' => 'rejected',
                    'last_stage' => $lastStage
                ]);
                
                // Update applicant status to match the latest application status
                $latestApplication = $applicant->applications()->latest()->first();
                if ($latestApplication) {
                    $applicant->update(['status' => $latestApplication->status]);
                }

                \Log::info("Application {$application->id} rejected at stage: {$lastStage} via bulk reject");
                $rejectedCount++;
            }

            return response()->json([
                'success' => true,
                'message' => "{$rejectedCount} applications rejected successfully"
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in bulk reject: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting applications: ' . $e->getMessage()
            ], 500);
        }
    }
}
