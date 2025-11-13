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
use Illuminate\Support\Facades\DB;
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
        
        // Only force account selection if user is not logged in (guest)
        // If already logged in, allow automatic login with existing account
        $socialiteDriver = Socialite::driver('google');
        if (!Auth::check()) {
            // User is not logged in, force account selection
            $socialiteDriver = $socialiteDriver->with(['prompt' => 'select_account']);
        }
        
        return $socialiteDriver->redirect();
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
            // Only force account selection if user is not logged in (guest)
            // If already logged in, allow automatic login with existing account
            $socialiteDriver = Socialite::driver($driver);
            if (!Auth::check()) {
                // User is not logged in, force account selection
                $socialiteDriver = $socialiteDriver->with(['prompt' => 'select_account']);
            }
            return $socialiteDriver->redirect();
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
            
            // Add logging for debugging concurrent login issues
            \Log::info('Social Login: Starting callback', [
                'driver' => $driver,
                'provider' => $provider_name,
                'session_id' => session()->getId(),
                'ip_address' => request()->ip(),
            ]);
            
            $callbackUser = Socialite::driver($provider_name)->user();
            
            \Log::info('Social Login: User retrieved from provider', [
                'driver' => $driver,
                'provider_id' => $callbackUser->getId(),
                'email' => $callbackUser->getEmail(),
            ]);
            
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
            
            // Get email from social provider
            $socialEmail = $callbackUser->getEmail();
            
            // If email is null, log warning but continue (some providers might not provide email)
            if (empty($socialEmail)) {
                \Log::warning('Social Login: Email is null from provider', [
                    'provider' => $provider_name,
                    'provider_id' => $callbackUser->getId(),
                ]);
            }
            
            $user = null;
            if (!empty($socialEmail)) {
                $user = User::where('email', $socialEmail)->first();
            }
            
            if ($user) {
                // Sync email to applicant if applicant exists and email is null
                $applicant = Applicant::where('user_id', $user->id)->first();
                if ($applicant && !$applicant->email && !empty($socialEmail)) {
                    $applicant->update(['email' => $socialEmail]);
                    \Log::info('Social Login: Synced email from user to applicant on login', [
                        'applicant_id' => $applicant->id,
                        'user_id' => $user->id,
                        'email' => $socialEmail,
                    ]);
                }
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
                        // Use database transaction to prevent race conditions during login
                        try {
                            DB::beginTransaction();
                            
                            // Regenerate session ID to prevent session fixation
                            session()->regenerate();
                            
                            Auth::guard('web')->login($user, true);
                            
                            DB::commit();
                            
                            \Log::info('Social Login: User logged in successfully', [
                                'user_id' => $user->id,
                                'email' => $user->email,
                                'driver' => $driver,
                            ]);
                            
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
                        } catch (\Exception $e) {
                            DB::rollBack();
                            \Log::error('Social Login: Error during login', [
                                'error' => $e->getMessage(),
                                'user_id' => $user->id,
                                'driver' => $driver,
                                'trace' => $e->getTraceAsString(),
                            ]);
                            throw $e;
                        }
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
                        
                        // Use database transaction to prevent race conditions during login
                        try {
                            DB::beginTransaction();
                            
                            // Regenerate session ID to prevent session fixation
                            session()->regenerate();
                            
                            Auth::guard('web')->login($user, true);
                            
                            DB::commit();
                            
                            \Log::info('Social Login: User logged in successfully (new socialite record)', [
                                'user_id' => $user->id,
                                'email' => $user->email,
                                'driver' => $driver,
                            ]);
                            
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
                        } catch (\Exception $e) {
                            DB::rollBack();
                            \Log::error('Social Login: Error during login (new socialite record)', [
                                'error' => $e->getMessage(),
                                'user_id' => $user->id,
                                'driver' => $driver,
                                'trace' => $e->getTraceAsString(),
                            ]);
                            throw $e;
                        }
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
                    
                    // Use database transaction to prevent race conditions during login
                    try {
                        DB::beginTransaction();
                        
                        // Regenerate session ID to prevent session fixation
                        session()->regenerate();
                        
                        Auth::guard('web')->login($user, true);
                        
                        DB::commit();
                        
                        \Log::info('Social Login: New user created and logged in successfully', [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'driver' => $driver,
                        ]);
                        
                        $notification = __('Logged in successfully.');
                        $notification = ['message' => $notification, 'alert-type' => 'success'];

                        $intendedUrl = session()->get('url.intended');
                        if ($intendedUrl && Str::contains($intendedUrl, '/admin')) {
                            return redirect()->route('dashboard');
                        }
                        return redirect()->intended(route('dashboard'))->with($notification);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        \Log::error('Social Login: Error during login (new user)', [
                            'error' => $e->getMessage(),
                            'user_id' => $user->id ?? 'unknown',
                            'driver' => $driver,
                            'trace' => $e->getTraceAsString(),
                        ]);
                        throw $e;
                    }
                } else {
                    \Log::error('Social Login: Failed to create new user', [
                        'email' => $callbackUser->getEmail(),
                        'driver' => $driver,
                    ]);
                    $notification = __('Login Failed');
                    $notification = ['message' => $notification, 'alert-type' => 'error'];
                    return redirect()->back()->with($notification);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Social Login: Unhandled exception in callback', [
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
                'driver' => $driver,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'session_id' => session()->getId(),
                'ip_address' => request()->ip(),
            ]);
            return to_route('login')->with('error', 'Login failed. Please try again.');
        }
    }

    public function handleDriverCallback($driver) {
        return $this->handleSocialCallback($driver);
    }

    private function handleApplicantRegistration($callbackUser, $provider_name, $applicantId)
    {
        try {
            $applicant = Applicant::findOrFail($applicantId);
            
            // Get email from social provider, fallback to existing email if null
            $socialEmail = $callbackUser->getEmail();
            if (empty($socialEmail)) {
                \Log::warning('Social Login: Email is null from provider', [
                    'provider' => $provider_name,
                    'applicant_id' => $applicantId,
                    'provider_id' => $callbackUser->getId(),
                ]);
                // Try to get email from user if already linked
                if ($applicant->user_id) {
                    $user = \App\Models\User::find($applicant->user_id);
                    if ($user && $user->email) {
                        $socialEmail = $user->email;
                        \Log::info('Social Login: Using email from linked user', [
                            'user_id' => $user->id,
                            'email' => $socialEmail,
                        ]);
                    }
                }
            }
            
            // Update applicant with social login info
            $updateData = [
                'name' => $callbackUser->getName() ?: $applicant->name,
            ];
            
            // Only update email if we have a valid email
            if (!empty($socialEmail)) {
                $updateData['email'] = $socialEmail;
            }
            
            $applicant->update($updateData);
            
            \Log::info('Social Login: Applicant updated with social info', [
                'applicant_id' => $applicant->id,
                'email' => $updateData['email'] ?? 'not updated',
                'provider' => $provider_name,
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
        // Get email from social provider
        $socialEmail = $callbackUser->getEmail();
        
        // Check if user already exists by email
        $user = null;
        if (!empty($socialEmail)) {
            $user = User::where('email', $socialEmail)->first();
        }
        
        if ($user) {
            // Sync email to applicant if applicant exists and email is null
            $applicant = Applicant::where('user_id', $user->id)->first();
            if ($applicant && !$applicant->email && !empty($socialEmail)) {
                $applicant->update(['email' => $socialEmail]);
                \Log::info('Social Login: Synced email from user to applicant in createOrFindUser', [
                    'applicant_id' => $applicant->id,
                    'user_id' => $user->id,
                    'email' => $socialEmail,
                ]);
            }
            
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
            $socialEmail = $callbackUser->getEmail();
            if (empty($socialEmail)) {
                // Generate a temporary email if provider doesn't provide one
                $socialEmail = 'user_' . $callbackUser->getId() . '@' . strtolower($provider_name) . '.temp';
                \Log::warning('Social Login: Email is null, using temporary email', [
                    'provider' => $provider_name,
                    'provider_id' => $callbackUser->getId(),
                    'temp_email' => $socialEmail,
                ]);
            }
            
            $user = User::create([
                'name' => $callbackUser->getName() ?: 'User',
                'email' => $socialEmail,
                'status' => UserStatus::ACTIVE->value,
                'is_banned' => UserStatus::UNBANNED->value,
                'email_verified_at' => !empty($callbackUser->getEmail()) ? now() : null,
                'password' => Hash::make(Str::random(10)), // Random password for social login
            ]);
            
            // Sync email to applicant if applicant exists and email is null
            $applicant = Applicant::where('user_id', $user->id)->first();
            if ($applicant && !$applicant->email && !empty($socialEmail) && strpos($socialEmail, '@') !== false) {
                $applicant->update(['email' => $socialEmail]);
                \Log::info('Social Login: Synced email from user to applicant', [
                    'applicant_id' => $applicant->id,
                    'user_id' => $user->id,
                    'email' => $socialEmail,
                ]);
            }

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
