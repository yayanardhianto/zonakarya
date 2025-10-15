<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\JobVacancy;
use App\Models\Talent;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        // Get all applications with their related data
        $query = Application::with(['user', 'applicant', 'jobVacancy', 'testSession.package']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
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

        // Get status counts if job_vacancy_id filter is applied
        $statusCounts = [];
        if ($request->filled('job_vacancy_id')) {
            // Get all possible statuses
            $allStatuses = ['pending', 'sent', 'check', 'short_call', 'group_interview', 'test_psychology', 'ojt', 'final_interview', 'sent_offering_letter', 'rejected', 'rejected_by_applicant'];
            
            // Get counts for existing statuses
            $existingCounts = Application::where('job_vacancy_id', $request->job_vacancy_id)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
            
            // Initialize all statuses with 0, then merge with existing counts
            $statusCounts = array_fill_keys($allStatuses, 0);
            $statusCounts = array_merge($statusCounts, $existingCounts);
        }

        return view('admin.applicants.index', compact('applications', 'jobVacancies', 'statusCounts'));
    }

    public function show(Applicant $applicant)
    {
        $applicant->load(['applications.jobVacancy', 'applications.testSession']);
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
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'notes' => 'nullable|string|max:1000',
            'template_id' => 'required|exists:whatsapp_templates,id'
        ]);

        $template = \App\Models\WhatsAppTemplate::findOrFail($request->template_id);
        
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
        $expectedTemplateType = $this->getExpectedTemplateType($application->status);
        if ($template->type !== $expectedTemplateType) {
            return response()->json([
                'success' => false,
                'message' => "Invalid template type. Expected: {$expectedTemplateType}, Got: {$template->type}"
            ], 400);
        }

        // Update application status
        $application->update([
            'status' => $nextStatus,
            'notes' => $request->notes
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

            // Load job vacancy data
            $application->load('jobVacancy');

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

            return response()->json([
                'success' => true,
                'applicant' => [
                    'id' => $applicant->id,
                    'name' => $applicant->name,
                    'whatsapp' => $applicant->whatsapp
                ],
                'application' => [
                    'id' => $application->id,
                    'status' => $application->status
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
            
            // Update application status to group_interview
            $application->update(['status' => 'group_interview']);
            
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
            
            // Update application status to test_psychology
            $application->update(['status' => 'test_psychology']);
            
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
            
            // Don't update status here - it should be updated by nextStep method
            // $applicant->update(['status' => 'ojt']);

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
            
            // Don't update status here - it should be updated by nextStep method
            // $applicant->update(['status' => 'final_interview']);

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

            return response()->json([
                'success' => true,
                'message' => 'Application rejected successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in reject: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting application: ' . $e->getMessage()
            ], 500);
        }
    }
}
