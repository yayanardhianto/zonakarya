<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\TestPackage;
use App\Models\TestSession;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobApplicationController extends Controller
{

    public function storeApplication(Request $request, $jobVacancy)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'whatsapp' => 'required|string|max:20',
                'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            ]);

            // Handle file uploads
            $cvPath = $request->file('cv')->store('applications/cv', 'public');
            $photoPath = $request->file('photo')->store('applications/photos', 'public');

            // Check if user is already logged in
            $isLoggedIn = Auth::check();
            $user = $isLoggedIn ? Auth::user() : null;

            // Check if applicant already exists for this user
            $applicant = null;
            if ($isLoggedIn && $user) {
                $applicant = Applicant::where('user_id', $user->id)->first();
            }

            // Create applicant if not exists
            if (!$applicant) {
                $applicant = Applicant::create([
                    'user_id' => $user ? $user->id : null,
                    'name' => $user ? $user->name : $request->name,
                    'email' => $user ? $user->email : null,
                    'phone' => null, // Will be filled after social login if not logged in
                    'whatsapp' => $request->whatsapp,
                    'cv_path' => $cvPath,
                    'photo_path' => $photoPath,
                    'status' => 'pending',
                ]);
            } else {
                // Update existing applicant with new CV and photo
                $applicant->update([
                    'cv_path' => $cvPath,
                    'photo_path' => $photoPath,
                    'whatsapp' => $request->whatsapp,
                ]);
            }

            // Resolve job from route parameter
            $job = JobVacancy::findOrFail($jobVacancy);
            
            \Log::info('JobApplicationController Debug:', [
                'job_vacancy_param' => $jobVacancy,
                'job_object' => $job,
                'job_id' => $job->id,
                'job_position' => $job->position
            ]);
            $jobId = $job->id;
            
            // Create application
            $application = Application::create([
                'user_id' => $user ? $user->id : null,
                'applicant_id' => $applicant->id,
                'job_vacancy_id' => $jobId,
                'status' => 'pending',
            ]);

            // Check if user has completed screening test
            $screeningResult = $this->getUserScreeningResult($user);
            
            \Log::info('Screening Test Check Debug:', [
                'is_logged_in' => $isLoggedIn,
                'user_id' => $user ? $user->id : null,
                'applicant_id' => $applicant->id,
                'screening_result' => $screeningResult ? [
                    'id' => $screeningResult->id,
                    'is_passed' => $screeningResult->is_passed,
                    'score' => $screeningResult->score,
                    'completed_at' => $screeningResult->completed_at
                ] : null
            ]);
            
            // Check screening result regardless of login status
            if ($screeningResult && $screeningResult->is_passed) {
                \Log::info('Reusing existing screening result, skipping test invitation');
                // Reuse screening result, skip test invitation
                $this->createApplicationWithExistingScreening($applicant, $application, $screeningResult);
                
                // Send waiting notification (tanpa URL test)
                $this->sendWaitingNotification($applicant);
            } else {
                \Log::info('No existing screening result or not passed, sending test invitation');
                // Send screening test invitation
                $this->sendTestInvitation($applicant);
            }

            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully!',
                'applicant_id' => $applicant->id,
                'application_id' => $application->id,
                'is_logged_in' => $isLoggedIn,
            ]);

        } catch (\Exception $e) {
            \Log::error('Application submission error:', [
                'error' => $e->getMessage(),
                'job_id' => $job->id ?? 'unknown',
                'request_data' => $request->except(['cv', 'photo'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error submitting application. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function completeRegistration(Request $request)
    {
        $request->validate([
            'applicant_id' => 'required|exists:applicants,id',
            'provider' => 'required|in:google,linkedin',
            'provider_id' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'avatar' => 'nullable|string',
        ]);

        $applicant = Applicant::findOrFail($request->applicant_id);
        
        $applicant->update([
            'provider' => $request->provider,
            'provider_id' => $request->provider_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $applicant->whatsapp, // Use WhatsApp as phone number
            'avatar' => $request->avatar,
            'email_verified_at' => now(),
        ]);

        // Send test invitation
        $this->sendTestInvitation($applicant);

        return response()->json([
            'success' => true,
            'message' => 'Registration completed! Test invitation sent.',
        ]);
    }

    private function sendTestInvitation(Applicant $applicant)
    {
        // Get screening test package (is_screening_test = true, active)
        $testPackage = TestPackage::where('is_screening_test', true)
            ->where('is_active', true)
            ->first();

        if (!$testPackage) {
            return false;
        }

        // Create test session
        $testSession = TestSession::create([
            'user_id' => $applicant->user_id,
            'package_id' => $testPackage->id,
            'applicant_id' => $applicant->id,
            'status' => 'pending',
            'access_token' => Str::random(60),
            'expires_at' => now()->addDay(),
        ]);

        // Update application with test session
        $application = $applicant->applications()->latest()->first();
        if ($application) {
            $application->update([
                'test_session_id' => $testSession->id,
                'status' => 'sent',
                'test_sent_at' => now(),
            ]);
        }

        // Update applicant status
        $applicant->update(['status' => 'sent']);

        // TODO: Send WhatsApp notification
        $this->sendWhatsAppNotification($applicant, $testSession);

        return true;
    }

    private function sendWhatsAppNotification(Applicant $applicant, TestSession $testSession)
    {
        $whatsappService = new WhatsAppService();
        $testUrl = route('test.take', ['session' => $testSession, 'token' => $testSession->access_token]);
        
        return $whatsappService->sendTestInvitation($applicant, $testUrl);
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
        // Update application with existing test session
        $application->update([
            'test_session_id' => $screeningResult->id,
            'status' => 'check', // Status langsung ke 'check' karena test sudah selesai
            'test_sent_at' => $screeningResult->completed_at,
            'test_completed_at' => $screeningResult->completed_at,
            'test_score' => $screeningResult->score,
        ]);

        // Update applicant status
        $applicant->update(['status' => 'check']);
    }

    private function sendWaitingNotification(Applicant $applicant)
    {
        $whatsappService = new WhatsAppService();
        $message = "Halo {$applicant->name}, aplikasi Anda telah diterima. " .
                   "Kami sedang memproses hasil test screening Anda. " .
                   "Mohon tunggu informasi selanjutnya dari tim HR kami.";
        
        return $whatsappService->sendMessage($applicant->whatsapp, $message);
    }
}
