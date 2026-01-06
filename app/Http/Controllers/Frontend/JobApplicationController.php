<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\TestPackage;
use App\Models\TestSession;
use App\Services\WhatsAppService;
use App\Helpers\PdfCompressor;
use App\Helpers\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobApplicationController extends Controller
{

    public function storeApplication(Request $request, JobVacancy $jobVacancy)
    {
        // Log request immediately, even before validation
        \Log::info('Job Application: Request received', [
            'job_vacancy_id' => $jobVacancy->id ?? 'unknown',
            'job_unique_code' => $jobVacancy->unique_code ?? 'unknown',
            'job_position' => $jobVacancy->position ?? 'unknown',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_logged_in' => Auth::check(),
            'user_id' => Auth::id(),
            'has_cv' => $request->hasFile('cv'),
            'has_photo' => $request->hasFile('photo'),
            'cv_size' => $request->hasFile('cv') ? $request->file('cv')->getSize() : null,
            'photo_size' => $request->hasFile('photo') ? $request->file('photo')->getSize() : null,
            'content_length' => $request->header('Content-Length'),
            'content_type' => $request->header('Content-Type'),
        ]);

        try {
            // Validate request
            \Log::info('Job Application: Validating request data', [
                'has_name' => $request->has('name'),
                'has_whatsapp' => $request->has('whatsapp'),
                'has_cv' => $request->hasFile('cv'),
                'has_photo' => $request->hasFile('photo'),
                'cv_size' => $request->hasFile('cv') ? $request->file('cv')->getSize() : null,
                'photo_size' => $request->hasFile('photo') ? $request->file('photo')->getSize() : null,
            ]);

            $request->validate([
                'name' => 'required|string|max:255',
                'whatsapp' => 'required|string|max:20',
                'cv' => 'required|file|mimes:pdf,doc,docx|max:25600', // 25MB = 25600 KB
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB = 5120 KB
            ]);

            \Log::info('Job Application: Validation passed');

            // Handle file uploads
            \Log::info('Job Application: Starting file uploads');
            try {
                $cvPath = $request->file('cv')->store('applications/cv', 'public');
                $cvFullPath = Storage::disk('public')->path($cvPath);
                $originalSize = filesize($cvFullPath);
                
                // Compress PDF if it's a PDF file
                if ($request->file('cv')->getMimeType() === 'application/pdf') {
                    \Log::info('Job Application: Attempting to compress PDF', [
                        'cv_path' => $cvPath,
                        'original_size' => $originalSize
                    ]);
                    PdfCompressor::compress($cvFullPath);
                    $compressedSize = filesize($cvFullPath);
                    \Log::info('Job Application: PDF compression completed', [
                        'cv_path' => $cvPath,
                        'original_size' => $originalSize,
                        'compressed_size' => $compressedSize,
                        'savings' => $originalSize - $compressedSize
                    ]);
                }
                
                \Log::info('Job Application: CV uploaded successfully', [
                    'cv_path' => $cvPath,
                    'cv_size' => filesize($cvFullPath),
                    'cv_mime' => $request->file('cv')->getMimeType(),
                ]);
            } catch (\Exception $e) {
                \Log::error('Job Application: CV upload failed', [
                    'error' => $e->getMessage(),
                    'cv_file' => $request->file('cv') ? $request->file('cv')->getClientOriginalName() : 'null',
                ]);
                throw $e;
            }

            try {
                $photoPath = $request->file('photo')->store('applications/photos', 'public');
                $photoFullPath = Storage::disk('public')->path($photoPath);
                $originalSize = filesize($photoFullPath);
                
                // Compress and resize image if it's an image file
                if (in_array($request->file('photo')->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'])) {
                    \Log::info('Job Application: Attempting to compress and resize image', [
                        'photo_path' => $photoPath,
                        'original_size' => $originalSize
                    ]);
                    
                    // First resize if needed (max 1920x1920 to keep reasonable size)
                    ImageCompressor::resizeIfNeeded($photoFullPath, 1920, 1920);
                    
                    // Then compress with quality 85
                    ImageCompressor::compress($photoFullPath, 85);
                    
                    $compressedSize = filesize($photoFullPath);
                    \Log::info('Job Application: Image compression completed', [
                        'photo_path' => $photoPath,
                        'original_size' => $originalSize,
                        'compressed_size' => $compressedSize,
                        'savings' => $originalSize - $compressedSize
                    ]);
                }
                
                \Log::info('Job Application: Photo uploaded successfully', [
                    'photo_path' => $photoPath,
                    'photo_size' => filesize($photoFullPath),
                    'photo_mime' => $request->file('photo')->getMimeType(),
                ]);
            } catch (\Exception $e) {
                \Log::error('Job Application: Photo upload failed', [
                    'error' => $e->getMessage(),
                    'photo_file' => $request->file('photo') ? $request->file('photo')->getClientOriginalName() : 'null',
                ]);
                // Clean up CV if photo upload fails
                if (isset($cvPath)) {
                    Storage::disk('public')->delete($cvPath);
                }
                throw $e;
            }

            // Check if user is already logged in
            $isLoggedIn = Auth::check();
            $user = $isLoggedIn ? Auth::user() : null;

            \Log::info('Job Application: Checking user authentication', [
                'is_logged_in' => $isLoggedIn,
                'user_id' => $user ? $user->id : null,
                'user_email' => $user ? $user->email : null,
            ]);

            // Check if applicant already exists for this user
            $applicant = null;
            if ($isLoggedIn && $user) {
                $applicant = Applicant::where('user_id', $user->id)->first();
                \Log::info('Job Application: Checking existing applicant', [
                    'user_id' => $user->id,
                    'applicant_found' => $applicant ? true : false,
                    'applicant_id' => $applicant ? $applicant->id : null,
                ]);
            }

            // Check if applicant already applied to this job vacancy
            // Check by email if not logged in, or by applicant_id if logged in
            $existingApplication = null;
            if ($applicant) {
                // If logged in and applicant exists, check by applicant_id
                $existingApplication = Application::where('applicant_id', $applicant->id)
                    ->where('job_vacancy_id', $jobVacancy->id)
                    ->first();
            } else {
                // If not logged in, check by whatsapp (most reliable identifier)
                if ($request->has('whatsapp')) {
                    // Normalize WhatsApp number
                    $whatsapp = preg_replace('/[^0-9]/', '', $request->whatsapp);
                    if (substr($whatsapp, 0, 1) === '0') {
                        $whatsapp = '62' . substr($whatsapp, 1);
                    } elseif (substr($whatsapp, 0, 2) !== '62') {
                        $whatsapp = '62' . $whatsapp;
                    }
                    
                    // Try to find applicant by whatsapp (check multiple formats)
                    $existingApplicant = Applicant::where(function($query) use ($whatsapp) {
                        $query->where('whatsapp', $whatsapp)
                              ->orWhere('whatsapp', '0' . substr($whatsapp, 2)) // Also check with 0 prefix
                              ->orWhere('whatsapp', '+' . $whatsapp) // Also check with + prefix
                              ->orWhere('whatsapp', preg_replace('/[^0-9]/', '', $whatsapp)); // Also check without any prefix
                    })->first();
                    
                    if ($existingApplicant) {
                        $existingApplication = Application::where('applicant_id', $existingApplicant->id)
                            ->where('job_vacancy_id', $jobVacancy->id)
                            ->first();
                    }
                }
            }

            if ($existingApplication) {
                \Log::warning('Job Application: Duplicate application detected', [
                    'applicant_id' => $applicant ? $applicant->id : null,
                    'job_vacancy_id' => $jobVacancy->id,
                    'existing_application_id' => $existingApplication->id,
                    'existing_status' => $existingApplication->status,
                ]);
                
                // Clean up uploaded files
                if (isset($cvPath)) {
                    Storage::disk('public')->delete($cvPath);
                }
                if (isset($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }
                
                return response()->json([
                    'success' => false,
                    'message' => __('Anda sudah pernah melamar untuk lowongan ini sebelumnya.'),
                    'duplicate' => true,
                    'existing_application' => [
                        'id' => $existingApplication->id,
                        'status' => $existingApplication->status,
                        'created_at' => $existingApplication->created_at->format('d M Y H:i'),
                    ]
                ], 400);
            }

            // Create applicant if not exists
            if (!$applicant) {
                \Log::info('Job Application: Creating new applicant', [
                    'name' => $user ? $user->name : $request->name,
                    'email' => $user ? $user->email : null,
                    'whatsapp' => $request->whatsapp,
                    'user_id' => $user ? $user->id : null,
                ]);

                try {
                    // Get email from user if logged in, otherwise leave null (will be filled by social login)
                    $applicantEmail = $user ? $user->email : null;
                    
                    $applicant = Applicant::create([
                        'user_id' => $user ? $user->id : null,
                        'name' => $user ? $user->name : $request->name,
                        'email' => $applicantEmail,
                        'phone' => null, // Will be filled after social login if not logged in
                        'whatsapp' => $request->whatsapp,
                        'cv_path' => $cvPath,
                        'photo_path' => $photoPath,
                        'status' => 'pending',
                    ]);
                    
                    // If user is logged in but applicant email is still null, sync from user
                    if ($user && !$applicant->email && $user->email) {
                        $applicant->update(['email' => $user->email]);
                        \Log::info('Job Application: Synced email from user to applicant', [
                            'applicant_id' => $applicant->id,
                            'user_id' => $user->id,
                            'email' => $user->email,
                        ]);
                    }

                    \Log::info('Job Application: Applicant created successfully', [
                        'applicant_id' => $applicant->id,
                        'name' => $applicant->name,
                        'whatsapp' => $applicant->whatsapp,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Job Application: Failed to create applicant', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    // Clean up uploaded files
                    Storage::disk('public')->delete([$cvPath, $photoPath]);
                    throw $e;
                }
            } else {
                // Update existing applicant with new CV and photo
                \Log::info('Job Application: Updating existing applicant', [
                    'applicant_id' => $applicant->id,
                    'old_cv_path' => $applicant->cv_path,
                    'old_photo_path' => $applicant->photo_path,
                ]);

                try {
                    // Delete old files
                    if ($applicant->cv_path) {
                        Storage::disk('public')->delete($applicant->cv_path);
                    }
                    if ($applicant->photo_path) {
                        Storage::disk('public')->delete($applicant->photo_path);
                    }

                    // Prepare update data
                    $updateData = [
                        'cv_path' => $cvPath,
                        'photo_path' => $photoPath,
                        'whatsapp' => $request->whatsapp,
                    ];
                    
                    // Sync email from user if applicant email is null and user has email
                    if (!$applicant->email && $user && $user->email) {
                        $updateData['email'] = $user->email;
                        \Log::info('Job Application: Syncing email from user to existing applicant', [
                            'applicant_id' => $applicant->id,
                            'user_id' => $user->id,
                            'email' => $user->email,
                        ]);
                    }

                    $applicant->update($updateData);

                    \Log::info('Job Application: Applicant updated successfully', [
                        'applicant_id' => $applicant->id,
                        'new_cv_path' => $cvPath,
                        'new_photo_path' => $photoPath,
                        'email_synced' => isset($updateData['email']),
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Job Application: Failed to update applicant', [
                        'applicant_id' => $applicant->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    // Clean up uploaded files
                    Storage::disk('public')->delete([$cvPath, $photoPath]);
                    throw $e;
                }
            }

            // Use the job from model binding
            $job = $jobVacancy;
            $jobId = $job->id;
            
            \Log::info('Job Application: Creating application record', [
                'job_id' => $jobId,
                'job_position' => $job->position,
                'applicant_id' => $applicant->id,
                'user_id' => $user ? $user->id : null,
            ]);

            // Create application
            try {
                $application = Application::create([
                    'user_id' => $user ? $user->id : null,
                    'applicant_id' => $applicant->id,
                    'job_vacancy_id' => $jobId,
                    'status' => 'pending',
                ]);

                \Log::info('Job Application: Application created successfully', [
                    'application_id' => $application->id,
                    'applicant_id' => $applicant->id,
                    'job_vacancy_id' => $jobId,
                    'status' => $application->status,
                ]);
            } catch (\Exception $e) {
                \Log::error('Job Application: Failed to create application', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'applicant_id' => $applicant->id,
                    'job_id' => $jobId,
                ]);
                // Clean up uploaded files
                Storage::disk('public')->delete([$cvPath, $photoPath]);
                throw $e;
            }

            // Check if screening test is required (based on admin setting)
            // Value dari database adalah string, perlu dikonversi ke boolean
            $screeningTestSetting = \Modules\GlobalSetting\app\Models\Setting::where('key', 'require_screening_test')->first()?->value;
            $requireScreeningTest = $screeningTestSetting === null ? true : (bool)($screeningTestSetting == 1 || $screeningTestSetting === 'true' || $screeningTestSetting === true);
            
            \Log::info('Job Application: Checking if screening test is required', [
                'setting_value' => $screeningTestSetting,
                'require_screening_test' => $requireScreeningTest,
                'application_id' => $application->id,
            ]);

            // If screening test is NOT required, skip test and go directly to profile
            if (!$requireScreeningTest) {
                \Log::info('Job Application: Screening test disabled, skipping to profile page', [
                    'application_id' => $application->id,
                    'applicant_id' => $applicant->id,
                ]);

                // Update application status to 'check' (equivalent to completing screening)
                $application->update(['status' => 'check']);

                // Send notification that screening is skipped
                try {
                    $this->sendWaitingNotification($applicant);
                } catch (\Exception $e) {
                    \Log::error('Job Application: Failed to send waiting notification', [
                        'error' => $e->getMessage(),
                        'application_id' => $application->id,
                    ]);
                }

                \Log::info('Job Application: Application ready for profile completion (screening bypassed)', [
                    'application_id' => $application->id,
                    'applicant_id' => $applicant->id,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Application submitted successfully! Please complete your profile.',
                    'applicant_id' => $applicant->id,
                    'application_id' => $application->id,
                    'is_logged_in' => $isLoggedIn,
                    'skip_test' => true,
                ]);
            }

            // Check if user has completed screening test
            \Log::info('Job Application: Checking screening test result', [
                'is_logged_in' => $isLoggedIn,
                'user_id' => $user ? $user->id : null,
                'applicant_id' => $applicant->id,
            ]);

            $screeningResult = $this->getUserScreeningResult($user);
            
            \Log::info('Job Application: Screening test check completed', [
                'is_logged_in' => $isLoggedIn,
                'user_id' => $user ? $user->id : null,
                'applicant_id' => $applicant->id,
                'screening_result_found' => $screeningResult ? true : false,
                'screening_result' => $screeningResult ? [
                    'id' => $screeningResult->id,
                    'is_passed' => $screeningResult->is_passed,
                    'score' => $screeningResult->score,
                    'completed_at' => $screeningResult->completed_at ? $screeningResult->completed_at->toDateTimeString() : null,
                    'status' => $screeningResult->status,
                ] : null
            ]);
            
            // Check screening result regardless of login status
            if ($screeningResult && $screeningResult->is_passed) {
                \Log::info('Job Application: Reusing existing screening result, skipping test invitation', [
                    'screening_result_id' => $screeningResult->id,
                    'application_id' => $application->id,
                ]);

                try {
                    // Reuse screening result, skip test invitation
                    $this->createApplicationWithExistingScreening($applicant, $application, $screeningResult);
                    
                    \Log::info('Job Application: Application updated with existing screening result', [
                        'application_id' => $application->id,
                        'test_session_id' => $screeningResult->id,
                    ]);
                    
                    // Send waiting notification (tanpa URL test)
                    $this->sendWaitingNotification($applicant);
                } catch (\Exception $e) {
                    \Log::error('Job Application: Failed to process existing screening result', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'application_id' => $application->id,
                        'screening_result_id' => $screeningResult->id,
                    ]);
                    throw $e;
                }
            } else {
                \Log::info('Job Application: No existing screening result or not passed, sending test invitation', [
                    'applicant_id' => $applicant->id,
                    'application_id' => $application->id,
                ]);

                try {
                    // Send screening test invitation
                    $testInvitationResult = $this->sendTestInvitation($applicant);
                    
                    \Log::info('Job Application: Test invitation sent', [
                        'applicant_id' => $applicant->id,
                        'application_id' => $application->id,
                        'test_invitation_result' => $testInvitationResult,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Job Application: Failed to send test invitation', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'applicant_id' => $applicant->id,
                        'application_id' => $application->id,
                    ]);
                    // Don't throw - application is already created, just log the error
                }
            }

            \Log::info('Job Application: Application submission completed successfully', [
                'application_id' => $application->id,
                'applicant_id' => $applicant->id,
                'job_vacancy_id' => $jobId,
                'is_logged_in' => $isLoggedIn,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully!',
                'applicant_id' => $applicant->id,
                'application_id' => $application->id,
                'is_logged_in' => $isLoggedIn,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Job Application: Validation error', [
                'errors' => $e->errors(),
                'job_id' => $jobVacancy->id ?? 'unknown',
                'job_unique_code' => $jobVacancy->unique_code ?? 'unknown',
                'request_data' => $request->except(['cv', 'photo', '_token']),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'file_sizes' => [
                    'cv' => $request->hasFile('cv') ? $request->file('cv')->getSize() : null,
                    'photo' => $request->hasFile('photo') ? $request->file('photo')->getSize() : null,
                ],
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation error. Please check your input.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Enhanced error logging with more context
            \Log::error('Job Application: Application submission error', [
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'job_id' => $jobVacancy->id ?? 'unknown',
                'job_unique_code' => $jobVacancy->unique_code ?? 'unknown',
                'job_position' => $jobVacancy->position ?? 'unknown',
                'request_data' => $request->except(['cv', 'photo', '_token']),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'is_logged_in' => Auth::check(),
                'user_id' => Auth::id(),
                'has_cv' => $request->hasFile('cv'),
                'has_photo' => $request->hasFile('photo'),
                'cv_size' => $request->hasFile('cv') ? $request->file('cv')->getSize() : null,
                'photo_size' => $request->hasFile('photo') ? $request->file('photo')->getSize() : null,
                'content_length' => $request->header('Content-Length'),
                'server_info' => [
                    'upload_max_filesize' => ini_get('upload_max_filesize'),
                    'post_max_size' => ini_get('post_max_size'),
                    'max_execution_time' => ini_get('max_execution_time'),
                    'memory_limit' => ini_get('memory_limit'),
                ],
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error submitting application. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred while processing your application.'
            ], 500);
        }
    }

    public function completeRegistration(Request $request)
    {
        \Log::info('Job Application: Starting registration completion', [
            'applicant_id' => $request->applicant_id,
            'provider' => $request->provider,
            'ip_address' => $request->ip(),
        ]);

        try {
            $request->validate([
                'applicant_id' => 'required|exists:applicants,id',
                'provider' => 'required|in:google,linkedin',
                'provider_id' => 'required|string',
                'name' => 'required|string',
                'email' => 'required|email',
                'avatar' => 'nullable|string',
            ]);

            \Log::info('Job Application: Registration completion validation passed', [
                'applicant_id' => $request->applicant_id,
            ]);

            $applicant = Applicant::findOrFail($request->applicant_id);
            
            \Log::info('Job Application: Updating applicant with social login data', [
                'applicant_id' => $applicant->id,
                'provider' => $request->provider,
                'email' => $request->email,
            ]);

            $applicant->update([
                'provider' => $request->provider,
                'provider_id' => $request->provider_id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $applicant->whatsapp, // Use WhatsApp as phone number
                'avatar' => $request->avatar,
                'email_verified_at' => now(),
            ]);

            \Log::info('Job Application: Applicant updated successfully', [
                'applicant_id' => $applicant->id,
                'email' => $applicant->email,
            ]);

            // Send test invitation
            $testInvitationResult = $this->sendTestInvitation($applicant);

            \Log::info('Job Application: Registration completion finished', [
                'applicant_id' => $applicant->id,
                'test_invitation_sent' => $testInvitationResult,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registration completed! Test invitation sent.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Job Application: Registration completion validation error', [
                'errors' => $e->errors(),
                'applicant_id' => $request->applicant_id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Job Application: Registration completion error', [
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
                'trace' => $e->getTraceAsString(),
                'applicant_id' => $request->applicant_id,
                'request_data' => $request->except(['_token']),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error completing registration. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred.'
            ], 500);
        }
    }

    private function sendTestInvitation(Applicant $applicant)
    {
        \Log::info('Job Application: Starting test invitation process', [
            'applicant_id' => $applicant->id,
            'applicant_name' => $applicant->name,
            'applicant_whatsapp' => $applicant->whatsapp,
        ]);

        // Get screening test package (is_screening_test = true, active)
        $testPackage = TestPackage::where('is_screening_test', true)
            ->where('is_active', true)
            ->first();

        if (!$testPackage) {
            \Log::warning('Job Application: No active screening test package found', [
                'applicant_id' => $applicant->id,
            ]);
            return false;
        }

        \Log::info('Job Application: Screening test package found', [
            'test_package_id' => $testPackage->id,
            'test_package_name' => $testPackage->name,
            'applicant_id' => $applicant->id,
        ]);

        try {
            // Create test session
            $testSession = TestSession::create([
                'user_id' => $applicant->user_id,
                'package_id' => $testPackage->id,
                'applicant_id' => $applicant->id,
                'status' => 'pending',
                'access_token' => Str::random(60),
                'expires_at' => now()->addDay(),
            ]);

            \Log::info('Job Application: Test session created', [
                'test_session_id' => $testSession->id,
                'applicant_id' => $applicant->id,
                'package_id' => $testPackage->id,
                'access_token' => substr($testSession->access_token, 0, 10) . '...',
                'expires_at' => $testSession->expires_at->toDateTimeString(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Job Application: Failed to create test session', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'applicant_id' => $applicant->id,
                'package_id' => $testPackage->id,
            ]);
            return false;
        }

        // Update application with test session
        try {
            $application = $applicant->applications()->latest()->first();
            if ($application) {
                $application->update([
                    'test_session_id' => $testSession->id,
                    'status' => 'sent',
                    'test_sent_at' => now(),
                ]);

                \Log::info('Job Application: Application updated with test session', [
                    'application_id' => $application->id,
                    'test_session_id' => $testSession->id,
                    'status' => 'sent',
                ]);
            } else {
                \Log::warning('Job Application: No application found for applicant', [
                    'applicant_id' => $applicant->id,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Job Application: Failed to update application with test session', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'applicant_id' => $applicant->id,
                'test_session_id' => $testSession->id,
            ]);
        }

        // Update applicant status
        try {
            $applicant->update(['status' => 'sent']);
            \Log::info('Job Application: Applicant status updated to sent', [
                'applicant_id' => $applicant->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Job Application: Failed to update applicant status', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'applicant_id' => $applicant->id,
            ]);
        }

        // Send WhatsApp notification
        try {
            $whatsappResult = $this->sendWhatsAppNotification($applicant, $testSession);
            \Log::info('Job Application: WhatsApp notification sent', [
                'applicant_id' => $applicant->id,
                'test_session_id' => $testSession->id,
                'whatsapp_result' => $whatsappResult,
            ]);
        } catch (\Exception $e) {
            \Log::error('Job Application: Failed to send WhatsApp notification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'applicant_id' => $applicant->id,
                'test_session_id' => $testSession->id,
                'applicant_whatsapp' => $applicant->whatsapp,
            ]);
            // Don't fail the whole process if WhatsApp fails
        }

        return true;
    }

    private function sendWhatsAppNotification(Applicant $applicant, TestSession $testSession)
    {
        \Log::info('Job Application: Sending WhatsApp notification', [
            'applicant_id' => $applicant->id,
            'applicant_name' => $applicant->name,
            'applicant_whatsapp' => $applicant->whatsapp,
            'test_session_id' => $testSession->id,
        ]);

        try {
            $whatsappService = new WhatsAppService();
            $testUrl = route('test.take', ['session' => $testSession, 'token' => $testSession->access_token]);
            
            \Log::info('Job Application: WhatsApp test URL generated', [
                'test_url' => $testUrl,
                'applicant_id' => $applicant->id,
                'test_session_id' => $testSession->id,
            ]);
            
            $result = $whatsappService->sendTestInvitation($applicant, $testUrl);
            
            \Log::info('Job Application: WhatsApp notification sent successfully', [
                'applicant_id' => $applicant->id,
                'test_session_id' => $testSession->id,
                'whatsapp_result' => $result,
            ]);
            
            return $result;
        } catch (\Exception $e) {
            \Log::error('Job Application: WhatsApp notification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'applicant_id' => $applicant->id,
                'test_session_id' => $testSession->id,
                'applicant_whatsapp' => $applicant->whatsapp,
            ]);
            throw $e;
        }
    }

    private function getUserScreeningResult($user)
    {
        if (!$user) {
            \Log::info('getUserScreeningResult: No user provided');
            return null;
        }
        
        $applicant = Applicant::where('user_id', $user->id)->first();
        if (!$applicant) {
            \Log::info('getUserScreeningResult: No applicant found for user', ['user_id' => $user->id]);
            return null;
        }
        
        \Log::info('getUserScreeningResult: Looking for screening test for applicant', [
            'applicant_id' => $applicant->id,
            'user_id' => $user->id
        ]);
        
        $screeningResult = TestSession::where('applicant_id', $applicant->id)
            ->whereHas('package', function($query) {
                $query->where('is_screening_test', true);
            })
            ->where('status', 'completed')
            ->where('is_passed', true)
            ->first();
            
        \Log::info('getUserScreeningResult: Found screening result', [
            'screening_result' => $screeningResult ? [
                'id' => $screeningResult->id,
                'is_passed' => $screeningResult->is_passed,
                'score' => $screeningResult->score,
                'status' => $screeningResult->status
            ] : null
        ]);
        
        return $screeningResult;
    }

    private function createApplicationWithExistingScreening($applicant, $application, $screeningResult)
    {
        \Log::info('Job Application: Creating application with existing screening result', [
            'applicant_id' => $applicant->id,
            'application_id' => $application->id,
            'screening_result_id' => $screeningResult->id,
            'screening_score' => $screeningResult->score,
            'screening_is_passed' => $screeningResult->is_passed,
        ]);

        try {
            // Update application with existing test session
            $application->update([
                'test_session_id' => $screeningResult->id,
                'status' => 'check', // Status langsung ke 'check' karena test sudah selesai
                'test_sent_at' => $screeningResult->completed_at,
                'test_completed_at' => $screeningResult->completed_at,
                'test_score' => $screeningResult->score,
            ]);

            \Log::info('Job Application: Application updated with existing screening', [
                'application_id' => $application->id,
                'test_session_id' => $screeningResult->id,
                'status' => 'check',
            ]);

            // Update applicant status
            $applicant->update(['status' => 'check']);

            \Log::info('Job Application: Applicant status updated to check', [
                'applicant_id' => $applicant->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Job Application: Failed to create application with existing screening', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'applicant_id' => $applicant->id,
                'application_id' => $application->id,
                'screening_result_id' => $screeningResult->id,
            ]);
            throw $e;
        }
    }

    private function sendWaitingNotification(Applicant $applicant)
    {
        \Log::info('Job Application: Sending waiting notification', [
            'applicant_id' => $applicant->id,
            'applicant_name' => $applicant->name,
            'applicant_whatsapp' => $applicant->whatsapp,
        ]);

        try {
            $whatsappService = new WhatsAppService();
            $message = "Halo {$applicant->name}, aplikasi Anda telah diterima. " .
                       "Kami sedang memproses hasil test screening Anda. " .
                       "Mohon tunggu informasi selanjutnya dari tim HR kami.";
            
            \Log::info('Job Application: Waiting notification message prepared', [
                'applicant_id' => $applicant->id,
                'message_length' => strlen($message),
            ]);
            
            $result = $whatsappService->sendMessage($applicant->whatsapp, $message);
            
            \Log::info('Job Application: Waiting notification sent successfully', [
                'applicant_id' => $applicant->id,
                'whatsapp_result' => $result,
            ]);
            
            return $result;
        } catch (\Exception $e) {
            \Log::error('Job Application: Waiting notification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'applicant_id' => $applicant->id,
                'applicant_whatsapp' => $applicant->whatsapp,
            ]);
            // Don't throw - this is a notification, not critical
            return false;
        }
    }

    // Preliminary apply endpoint: create applicant + application, create a test session and return start_test_url
    public function applyDirectProfile(Request $request, JobVacancy $jobVacancy)
    {
        \Log::info('Job Application: applyDirectProfile called (skip test flow)', [
            'job_vacancy_id' => $jobVacancy->id,
            'job_position' => $jobVacancy->position,
            'is_logged_in' => Auth::check(),
        ]);

        // Check if screening test is required (based on admin setting)
        $screeningTestSetting = \Modules\GlobalSetting\app\Models\Setting::where('key', 'require_screening_test')->first()?->value;
        $requireScreeningTest = $screeningTestSetting === null ? true : (bool)($screeningTestSetting == 1 || $screeningTestSetting === 'true' || $screeningTestSetting === true);
        
        \Log::info('Job Application: Checking if screening test is required (applyDirectProfile)', [
            'setting_value' => $screeningTestSetting,
            'require_screening_test' => $requireScreeningTest,
        ]);

        // If screening is REQUIRED, reject this endpoint
        if ($requireScreeningTest) {
            return response()->json([
                'success' => false,
                'message' => 'Screening test is required for this application.',
            ], 400);
        }

        $user = Auth::user();
        $isLoggedIn = Auth::check();

        if (!$isLoggedIn) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to use this flow.',
            ], 401);
        }

        \Log::info('Job Application: Ready for direct profile (skip test)', [
            'job_vacancy_id' => $jobVacancy->id,
            'user_id' => $user->id,
            'skip_test' => true,
        ]);

        // Return success - Applicant & Application will be created when submitting profile
        return response()->json([
            'success' => true,
            'message' => 'Ready for profile completion',
            'job_vacancy_id' => $jobVacancy->id,
            'skip_test' => true,
        ]);
    }

    public function applyPrelim(Request $request, JobVacancy $jobVacancy)
    {
        \Log::info('Job Application: applyPrelim called', [
            'job_vacancy_id' => $jobVacancy->id ?? null,
            'ip' => $request->ip(),
            'user_id' => Auth::id(),
        ]);

        try {
            $isLoggedIn = Auth::check();
            $user = $isLoggedIn ? Auth::user() : null;

            // Only validate name & whatsapp if not logged in
            if (!$isLoggedIn) {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'whatsapp' => 'required|string|max:20',
                ]);
            }

            // Create or get applicant
            $applicant = null;
            
            if ($isLoggedIn && $user) {
                // If logged in, create minimal applicant (name & whatsapp will be filled in profile page later)
                $applicant = Applicant::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $user->name ?? 'Applicant',
                        'email' => $user->email,
                        'whatsapp' => '',
                        'status' => 'pending',
                    ]
                );
            } else {
                // Not logged in - must provide name & whatsapp
                $whatsapp = preg_replace('/[^0-9]/', '', $request->whatsapp);
                if (substr($whatsapp, 0, 1) === '0') {
                    $whatsapp = '62' . substr($whatsapp, 1);
                } elseif (substr($whatsapp, 0, 2) !== '62') {
                    $whatsapp = '62' . $whatsapp;
                }

                $applicant = Applicant::firstOrCreate(
                    ['whatsapp' => $whatsapp],
                    [
                        'name' => $request->name,
                        'whatsapp' => $whatsapp,
                        'status' => 'pending',
                    ]
                );
            }

            // CHECK: Prevent duplicate application for same job vacancy
            // Check if applicant already has an application for this job
            $existingApplication = Application::where('applicant_id', $applicant->id)
                ->where('job_vacancy_id', $jobVacancy->id)
                ->first();

            if ($existingApplication) {
                \Log::warning('Job Application: Duplicate application attempt', [
                    'applicant_id' => $applicant->id,
                    'job_vacancy_id' => $jobVacancy->id,
                    'existing_application_id' => $existingApplication->id,
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melamar di posisi ini sebelumnya. Tidak boleh melamar lebih dari sekali untuk posisi yang sama.',
                    'error' => 'duplicate_application',
                    'existing_application_id' => $existingApplication->id,
                ], 409);
            }

            // Create application with status 'check' (preliminary)
            $application = Application::create([
                'user_id' => $user ? $user->id : null,
                'applicant_id' => $applicant->id,
                'job_vacancy_id' => $jobVacancy->id,
                'status' => 'pending',
            ]);

            \Log::info('Job Application: Preliminary application created', [
                'application_id' => $application->id,
                'applicant_id' => $applicant->id,
            ]);

            // Check if screening test is required (based on admin setting)
            // Value dari database adalah string, perlu dikonversi ke boolean
            $screeningTestSetting = \Modules\GlobalSetting\app\Models\Setting::where('key', 'require_screening_test')->first()?->value;
            $requireScreeningTest = $screeningTestSetting === null ? true : (bool)($screeningTestSetting == 1 || $screeningTestSetting === 'true' || $screeningTestSetting === true);
            
            \Log::info('Job Application: Checking if screening test is required (applyPrelim)', [
                'setting_value' => $screeningTestSetting,
                'require_screening_test' => $requireScreeningTest,
                'application_id' => $application->id,
            ]);

            // If screening test is NOT required, reject this flow
            if (!$requireScreeningTest) {
                \Log::info('Job Application: Screening test disabled - should use applyDirectProfile instead', [
                    'application_id' => $application->id,
                    'applicant_id' => $applicant->id,
                ]);

                // Still create application & test session for backward compatibility
                // But this should not happen in normal flow
            }

            // Create test session (use screening package)
            $testPackage = TestPackage::where('is_screening_test', true)->where('is_active', true)->first();
            if ($testPackage) {
                $testSession = TestSession::create([
                    'user_id' => $applicant->user_id,
                    'package_id' => $testPackage->id,
                    'applicant_id' => $applicant->id,
                    'status' => 'pending',
                    'access_token' => Str::random(60),
                    'expires_at' => now()->addDay(),
                ]);

                // Attach test session to application (but keep status as 'check' per request)
                $application->update(['test_session_id' => $testSession->id]);

                \Log::info('Job Application: Test session created (prelim)', [
                    'test_session_id' => $testSession->id,
                    'application_id' => $application->id,
                ]);

                // Send WhatsApp invitation (uses existing helper that builds test.take URL)
                try {
                    $this->sendWhatsAppNotification($applicant, $testSession);
                } catch (\Exception $e) {
                    \Log::error('Job Application: Failed to send WhatsApp in applyPrelim', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'applicant_id' => $applicant->id,
                        'test_session_id' => $testSession->id,
                    ]);
                }

                $startTestUrl = route('test.take', ['session' => $testSession, 'token' => $testSession->access_token]);
            } else {
                $startTestUrl = null;
                \Log::warning('Job Application: No screening test package found during applyPrelim');
            }

            return response()->json([
                'success' => true,
                'message' => 'Preliminary application saved',
                'application_id' => $application->id,
                'applicant_id' => $applicant->id,
                'start_test_url' => $startTestUrl,
                'is_logged_in' => $isLoggedIn,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors(), 'message' => 'Validation error'], 422);
        } catch (\Exception $e) {
            \Log::error('Job Application: applyPrelim error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Error saving preliminary application'], 500);
        }
    }

    // Submit profile for skip test flow (creates applicant and application)
    public function submitProfileSkipTest(Request $request, JobVacancy $jobVacancy)
    {
        \Log::info('Job Application: submitProfileSkipTest called', [
            'job_vacancy_id' => $jobVacancy->id,
            'ip' => $request->ip(),
        ]);

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'whatsapp' => 'required|string|max:20',
                'cv' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg|max:25600',
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
            }

            // Check if screening test is required
            $screeningTestSetting = \Modules\GlobalSetting\app\Models\Setting::where('key', 'require_screening_test')->first()?->value;
            $requireScreeningTest = $screeningTestSetting === null ? true : (bool)($screeningTestSetting == 1 || $screeningTestSetting === 'true' || $screeningTestSetting === true);
            
            if ($requireScreeningTest) {
                return response()->json(['success' => false, 'message' => 'This endpoint is only for skip test flow'], 400);
            }

            // Get or create applicant
            $applicant = Applicant::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $request->name,
                    'email' => $user->email,
                    'whatsapp' => '',
                    'status' => 'pending',
                ]
            );

            // Normalize WhatsApp number
            $whatsapp = preg_replace('/[^0-9]/', '', $request->whatsapp);
            if (substr($whatsapp, 0, 1) === '0') {
                $whatsapp = '62' . substr($whatsapp, 1);
            } elseif (substr($whatsapp, 0, 2) !== '62') {
                $whatsapp = '62' . $whatsapp;
            }

            // Check if applicant already has an application for this job
            $existingApplication = Application::where('applicant_id', $applicant->id)
                ->where('job_vacancy_id', $jobVacancy->id)
                ->first();

            if ($existingApplication) {
                \Log::warning('Job Application: Duplicate application in submitProfileSkipTest', [
                    'applicant_id' => $applicant->id,
                    'job_vacancy_id' => $jobVacancy->id,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melamar di posisi ini sebelumnya.',
                    'error' => 'duplicate_application',
                ], 409);
            }

            // Handle file uploads
            $cvPath = $request->file('cv')->store('applications/cv', 'public');
            $photoPath = $request->file('photo')->store('applications/photos', 'public');

            // Update applicant with full data
            $applicant->update([
                'name' => $request->name,
                'whatsapp' => $whatsapp,
                'cv_path' => $cvPath,
                'photo_path' => $photoPath,
                'status' => 'check', // Status check untuk skip test flow (no screening test)
            ]);

            // CREATE Application now (for skip test flow)
            $application = Application::create([
                'user_id' => $user->id,
                'applicant_id' => $applicant->id,
                'job_vacancy_id' => $jobVacancy->id,
                'status' => 'check', // Status langsung check karena skip test
            ]);

            \Log::info('Job Application: submitProfileSkipTest completed', [
                'application_id' => $application->id,
                'applicant_id' => $applicant->id,
                'job_vacancy_id' => $jobVacancy->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully',
                'applicant_id' => $applicant->id,
                'application_id' => $application->id,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors(), 'message' => 'Validation error'], 422);
        } catch (\Exception $e) {
            \Log::error('Job Application: submitProfileSkipTest error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'job_vacancy_id' => $jobVacancy->id,
            ]);
            return response()->json(['success' => false, 'message' => 'Error submitting application'], 500);
        }
    }

    // Submit profile for skip test flow - ID version (for JSON route)
    public function submitProfileSkipTestById(Request $request, $id)
    {
        $jobVacancy = JobVacancy::find($id);
        if (!$jobVacancy) {
            return response()->json(['success' => false, 'message' => 'Job vacancy not found'], 404);
        }
        return $this->submitProfileSkipTest($request, $jobVacancy);
    }

    // Finalize application: upload CV and photo and set final status
    public function finalizeApplication(Request $request, Application $application)
    {
        \Log::info('Job Application: finalizeApplication called', ['application_id' => $application->id, 'ip' => $request->ip()]);

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'whatsapp' => 'required|string|max:20',
                'cv' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg|max:25600',
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ]);

            $applicant = $application->applicant;
            if (!$applicant) {
                return response()->json(['success' => false, 'message' => 'Applicant not found'], 404);
            }

            // Update name and whatsapp
            if ($request->has('name') && $request->name) {
                $applicant->name = $request->name;
            }
            
            if ($request->has('whatsapp') && $request->whatsapp) {
                // Normalize WhatsApp number
                $whatsapp = preg_replace('/[^0-9]/', '', $request->whatsapp);
                if (substr($whatsapp, 0, 1) === '0') {
                    $whatsapp = '62' . substr($whatsapp, 1);
                } elseif (substr($whatsapp, 0, 2) !== '62') {
                    $whatsapp = '62' . $whatsapp;
                }
                $applicant->whatsapp = $whatsapp;
            }

            // Handle uploads
            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('applications/cv', 'public');
                // delete old
                if ($applicant->cv_path) {
                    Storage::disk('public')->delete($applicant->cv_path);
                }
                $applicant->cv_path = $cvPath;
            }

            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('applications/photos', 'public');
                if ($applicant->photo_path) {
                    Storage::disk('public')->delete($applicant->photo_path);
                }
                $applicant->photo_path = $photoPath;
            }

            $applicant->save(); 

            $application->update(['status' => 'check']);

            \Log::info('Job Application: Application finalized successfully', [
                'application_id' => $application->id,
                'applicant_id' => $applicant->id,
                'applicant_name' => $applicant->name,
                'applicant_whatsapp' => $applicant->whatsapp,
            ]);

            return response()->json(['success' => true, 'message' => 'Application finalized', 'applicant_id' => $applicant->id]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors(), 'message' => 'Validation error'], 422);
        } catch (\Exception $e) {
            \Log::error('Job Application: finalizeApplication error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'application_id' => $application->id]);
            return response()->json(['success' => false, 'message' => 'Error finalizing application'], 500);
        }
    }

    /**
     * Show applicant profile page after completing test.
     * This page replaces the previous finalize modal and prevents accidental back navigation.
     */
    /**
     * Show applicant profile page for skip test flow (no application created yet)
     */
    public function showProfileSkipTest(JobVacancy $jobVacancy)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                abort(401);
            }

            // Check if screening test is required
            $screeningTestSetting = \Modules\GlobalSetting\app\Models\Setting::where('key', 'require_screening_test')->first()?->value;
            $requireScreeningTest = $screeningTestSetting === null ? true : (bool)($screeningTestSetting == 1 || $screeningTestSetting === 'true' || $screeningTestSetting === true);
            
            if ($requireScreeningTest) {
                abort(400, 'This endpoint is only for skip test flow');
            }

            // Get or create minimal applicant
            $applicant = Applicant::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->name ?? 'Applicant',
                    'email' => $user->email,
                    'whatsapp' => '',
                    'status' => 'pending',
                ]
            );

            // Create a temporary application object for view (not persisted yet)
            // This will be replaced with real application when profile is submitted
            $application = new Application([
                'job_vacancy_id' => $jobVacancy->id,
                'applicant_id' => $applicant->id,
                'status' => 'pending',
            ]);
            $application->jobVacancy = $jobVacancy;
            
            $job = $jobVacancy;

            \Log::info('Job Application: showProfileSkipTest', [
                'user_id' => $user->id,
                'applicant_id' => $applicant->id,
                'job_vacancy_id' => $jobVacancy->id,
                'skip_test' => true,
            ]);

            // Pass a flag to indicate this is skip test flow
            return view('frontend.job-vacancy.applicant-profile', compact('application', 'job', 'applicant'))->with('skip_test', true);
        } catch (\Exception $e) {
            \Log::error('Job Application: showProfileSkipTest error', [
                'error' => $e->getMessage(),
                'job_vacancy_id' => $jobVacancy->id,
            ]);
            abort(404);
        }
    }

    public function showProfile(Application $application)
    {
        try {
            $application->load(['applicant', 'jobVacancy']);
            $job = $application->jobVacancy;
            $applicant = $application->applicant;

            return view('frontend.job-vacancy.applicant-profile', compact('application', 'job', 'applicant'));
        } catch (\Exception $e) {
            \Log::error('Job Application: showProfile error', ['error' => $e->getMessage(), 'application_id' => $application->id]);
            abort(404);
        }
    }

    /**
     * Get latest application for an applicant (API endpoint)
     */
    public function getLatestApplicationForApplicant(Applicant $applicant)
    {
        try {
            $application = Application::where('applicant_id', $applicant->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$application) {
                return response()->json([
                    'success' => false,
                    'message' => 'No application found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'application_id' => $application->id,
                'status' => $application->status,
                'created_at' => $application->created_at
            ]);
        } catch (\Exception $e) {
            \Log::error('Job Application: getLatestApplicationForApplicant error', [
                'error' => $e->getMessage(),
                'applicant_id' => $applicant->id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving application'
            ], 500);
        }
    }
}
