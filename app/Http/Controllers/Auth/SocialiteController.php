<?php

namespace App\Http\Controllers\Auth;

use App\Enums\SocialiteDriverType;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Applicant;
use App\Services\WhatsAppService;
use App\Traits\NewUserCreateTrait;
use App\Traits\SetConfigTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller {
    use NewUserCreateTrait, SetConfigTrait;

    public function __construct() {
        // Configuration is now set in individual methods
    }

    public function redirectToGoogle() {
        // Store applicant_id in session if provided
        $applicantId = request('applicant_id');
        if ($applicantId) {
            session(['applicant_id' => $applicantId]);
            \Log::info('Google redirect with applicant_id:', ['applicant_id' => $applicantId]);
        }
        
        // Store intended URL in session if provided
        $intendedUrl = request('intended');
        if ($intendedUrl) {
            session(['url.intended' => $intendedUrl]);
            \Log::info('Google redirect with intended URL:', ['intended' => $intendedUrl]);
        }
        
        // Set Google configuration
        self::setGoogleLoginInfo();
        
        return Socialite::driver('google')->redirect();
    }

    public function redirectToLinkedIn() {
        // Store applicant_id in session if provided
        $applicantId = request('applicant_id');
        if ($applicantId) {
            session(['applicant_id' => $applicantId]);
            \Log::info('LinkedIn redirect with applicant_id:', ['applicant_id' => $applicantId]);
        }
        
        // Store intended URL in session if provided
        $intendedUrl = request('intended');
        if ($intendedUrl) {
            session(['url.intended' => $intendedUrl]);
            \Log::info('LinkedIn redirect with intended URL:', ['intended' => $intendedUrl]);
        }
        
        // Set LinkedIn configuration
        self::setLinkedInLoginInfo();
        
        return Socialite::driver('linkedin')->redirect();
    }

    public function redirectToDriver($driver) {
        // Validate driver
        if (!in_array($driver, SocialiteDriverType::getAll())) {
            $notification = __('Invalid Social Login Type!');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
            return redirect()->back()->with($notification);
        }
        
        // Store applicant_id in session if provided
        $applicantId = request('applicant_id');
        if ($applicantId) {
            session(['applicant_id' => $applicantId]);
            \Log::info('Driver redirect with applicant_id:', ['driver' => $driver, 'applicant_id' => $applicantId]);
        }
        
        // Set configuration based on driver
        if ($driver == SocialiteDriverType::GOOGLE->value) {
            self::setGoogleLoginInfo();
        } elseif ($driver == SocialiteDriverType::LINKEDIN->value) {
            self::setLinkedInLoginInfo();
        }
        
        return Socialite::driver($driver)->redirect();
    }

    public function handleGoogleCallback() {
        return $this->handleSocialCallback('google');
    }

    public function handleLinkedInCallback() {
        return $this->handleSocialCallback('linkedin');
    }

    public function handleSocialCallback($driver) {
        if (!in_array($driver, SocialiteDriverType::getAll())) {
            $notification = __('Invalid Social Login Type!');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }
        try {
            $provider_name = SocialiteDriverType::from($driver)->value;
            $callbackUser = Socialite::driver($provider_name)->user();
            
            // Check if this is an applicant registration
            $applicantId = request('applicant_id') ?? session('applicant_id');
            \Log::info('Social Login Debug:', [
                'driver' => $driver,
                'applicant_id' => $applicantId,
                'session_applicant_id' => session('applicant_id'),
                'all_params' => request()->all(),
                'is_authenticated' => Auth::check()
            ]);
            
            // Priority: If this is applicant registration, handle it first
            // This should work even if user is already logged in
            if ($applicantId) {
                \Log::info('Handling applicant registration:', ['applicant_id' => $applicantId]);
                return $this->handleApplicantRegistration($callbackUser, $provider_name, $applicantId);
            }
            
            $user = User::where('email', $callbackUser->getEmail())->first();
            
            if ($user) {
                // User exists, check if socialite record exists
                $findDriver = $user
                    ->socialite()
                    ->where(['provider_name' => $provider_name, 'provider_id' => $callbackUser->getId()])
                    ->first();

                if ($findDriver) {
                    // Socialite record exists, check user status
                    if ($user->status == UserStatus::ACTIVE->value && $user->is_banned == UserStatus::UNBANNED->value) {
                        // Update email verification if needed
                        if ($user->email_verified_at == null) {
                            $user->update([
                                'email_verified_at' => now(),
                                'verification_token' => null
                            ]);
                        }
                        
                        // User is active and not banned, login
                        Auth::guard('web')->login($user, true);
                        $notification = __('Logged in successfully.');
                        $notification = ['message' => $notification, 'alert-type' => 'success'];

                        $intendedUrl = session()->get('url.intended');
                        if ($intendedUrl) {
                            // Clear the intended URL from session
                            session()->forget('url.intended');
                            return redirect($intendedUrl)->with($notification);
                        }
                        if ($intendedUrl && Str::contains($intendedUrl, '/admin')) {
                            return redirect()->route('dashboard');
                        }
                        return redirect()->intended(route('dashboard'))->with($notification);
                    } else {
                        // User exists but inactive or banned
                        $notification = __('Inactive account');
                        $notification = ['message' => $notification, 'alert-type' => 'error'];
                        return redirect()->back()->with($notification);
                    }
                } else {
                    // User exists but no socialite record, create one
                    $socialite = $this->createNewUser(callbackUser: $callbackUser, provider_name: $provider_name, user: $user);
                    
                    if ($socialite) {
                        // Update user status to active if needed and verify email
                        $updateData = [];
                        if ($user->status != UserStatus::ACTIVE->value) {
                            $updateData['status'] = UserStatus::ACTIVE->value;
                        }
                        if ($user->email_verified_at == null) {
                            $updateData['email_verified_at'] = now();
                            $updateData['verification_token'] = null;
                        }
                        
                        if (!empty($updateData)) {
                            $user->update($updateData);
                        }
                        
                        Auth::guard('web')->login($user, true);
                        $notification = __('Logged in successfully.');
                        $notification = ['message' => $notification, 'alert-type' => 'success'];

                        $intendedUrl = session()->get('url.intended');
                        if ($intendedUrl) {
                            // Clear the intended URL from session
                            session()->forget('url.intended');
                            return redirect($intendedUrl)->with($notification);
                        }
                        if ($intendedUrl && Str::contains($intendedUrl, '/admin')) {
                            return redirect()->route('dashboard');
                        }
                        return redirect()->intended(route('dashboard'))->with($notification);
                    } else {
                        $notification = __('Login Failed');
                        $notification = ['message' => $notification, 'alert-type' => 'error'];
                        return redirect()->back()->with($notification);
                    }
                }
            } else {
                // User doesn't exist, create new user
                $socialite = $this->createNewUser(callbackUser: $callbackUser, provider_name: $provider_name, user: false);

                if ($socialite) {
                    $user = User::find($socialite->user_id);
                    Auth::guard('web')->login($user, true);
                    $notification = __('Logged in successfully.');
                    $notification = ['message' => $notification, 'alert-type' => 'success'];

                    $intendedUrl = session()->get('url.intended');
                    if ($intendedUrl && Str::contains($intendedUrl, '/admin')) {
                        return redirect()->route('dashboard');
                    }
                    return redirect()->intended(route('dashboard'))->with($notification);
                } else {
                    $notification = __('Login Failed');
                    $notification = ['message' => $notification, 'alert-type' => 'error'];
                    return redirect()->back()->with($notification);
                }
            }
        } catch (\Exception $e) {
            return to_route('login');
        }
    }

    public function handleDriverCallback($driver) {
        return $this->handleSocialCallback($driver);
    }

    private function handleApplicantRegistration($callbackUser, $provider_name, $applicantId)
    {
        try {
            $applicant = Applicant::findOrFail($applicantId);
            
            // Update applicant with social login info
            $applicant->update([
                'name' => $callbackUser->getName(),
                'email' => $callbackUser->getEmail(),
            ]);

            // Create or find user account for future logins
            $user = $this->createOrFindUser($callbackUser, $provider_name);

            // Update applicant with user_id
            $applicant->update(['user_id' => $user->id]);

            // Send test invitation
            $this->sendTestInvitation($applicant);

            // Clear applicant_id from session
            session()->forget('applicant_id');

            return redirect()->route('jobs.thank-you', $applicant)
                ->with('success', 'Registration completed! Test invitation sent to your WhatsApp.');
                
        } catch (\Exception $e) {
            \Log::error('Applicant registration error:', [
                'applicant_id' => $applicantId,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()
                ->with('error', 'Registration failed. Please try again.');
        }
    }

    private function sendTestInvitation(Applicant $applicant)
    {
        try {
            // Get screening test package (category_id = 1, active)
            $testPackage = \App\Models\TestPackage::where('category_id', 1)
                ->where('is_active', true)
                ->first();

            if (!$testPackage) {
                \Log::error('No active test package found for screening');
                return false;
            }

            // Create test session
            $testSession = \App\Models\TestSession::create([
                'package_id' => $testPackage->id,
                'applicant_id' => $applicant->id,
                'user_id' => $applicant->user_id, // Add user_id to link with user account
                'status' => 'pending',
                'access_token' => Str::random(32),
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

            // Send WhatsApp notification using real API
            $result = $this->sendWhatsAppNotification($applicant, $testSession);
            
            if (!$result['success']) {
                \Log::error('Failed to send WhatsApp test invitation:', [
                    'applicant_id' => $applicant->id,
                    'error' => $result['error'] ?? 'Unknown error'
                ]);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Send test invitation error:', [
                'applicant_id' => $applicant->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    private function sendWhatsAppNotification(Applicant $applicant, $testSession)
    {
        $whatsappService = new WhatsAppService();
        $testUrl = route('test.take', ['session' => $testSession, 'token' => $testSession->access_token]);
        
        $result = $whatsappService->sendTestInvitation($applicant, $testUrl);
        
        // Log the result
        \Log::info('WhatsApp Test Invitation Result:', [
            'applicant_id' => $applicant->id,
            'whatsapp' => $applicant->whatsapp,
            'test_url' => $testUrl,
            'result' => $result
        ]);
        
        return $result;
    }

    private function createOrFindUser($callbackUser, $provider_name)
    {
        // Check if user already exists by email
        $user = User::where('email', $callbackUser->getEmail())->first();
        
        if ($user) {
            // User exists, check if socialite record exists
            $findDriver = $user
                ->socialite()
                ->where(['provider_name' => $provider_name, 'provider_id' => $callbackUser->getId()])
                ->first();

            if (!$findDriver) {
                // Create socialite record for existing user
                $user->socialite()->create([
                    'provider_name' => $provider_name,
                    'provider_id' => $callbackUser->getId(),
                    'access_token' => $callbackUser->token ?? null,
                    'refresh_token' => $callbackUser->refreshToken ?? null,
                ]);
            }
        } else {
            // Create new user
            $user = User::create([
                'name' => $callbackUser->getName(),
                'email' => $callbackUser->getEmail(),
                'status' => UserStatus::ACTIVE->value,
                'is_banned' => UserStatus::UNBANNED->value,
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random(10)), // Random password for social login
            ]);

            // Create socialite record
            $user->socialite()->create([
                'provider_name' => $provider_name,
                'provider_id' => $callbackUser->getId(),
                'access_token' => $callbackUser->token ?? null,
                'refresh_token' => $callbackUser->refreshToken ?? null,
            ]);
        }

        // Only auto-login if user is not already logged in
        if (!Auth::check()) {
            Auth::guard('web')->login($user, true);
            \Log::info('User auto-logged in after applicant registration:', [
                'user_id' => $user->id,
                'email' => $user->email,
                'provider' => $provider_name
            ]);
        } else {
            \Log::info('User already logged in, skipping auto-login:', [
                'user_id' => $user->id,
                'email' => $user->email,
                'provider' => $provider_name
            ]);
        }

        return $user;
    }
}
