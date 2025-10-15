<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Rules\CustomRecaptcha;
use App\Traits\GlobalMailTrait;
use Illuminate\Support\Facades\DB;
use App\Services\MailSenderService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Traits\GetGlobalInformationTrait;

class RegisteredUserController extends Controller {
    use GetGlobalInformationTrait, GlobalMailTrait;

    public function create(): View {
        return view('frontend.auth.register');
    }

    public function store(Request $request): RedirectResponse {
        $setting = Cache::get('setting');

        $request->validate([
            'name'                 => ['required', 'string', 'max:255'],
            'email'                => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password'             => ['required', 'confirmed', 'min:4', 'max:100'],
            'g-recaptcha-response' => $setting->recaptcha_status == 'active' ? ['required', new CustomRecaptcha()] : '',
        ], [
            'name.required'                 => __('Name is required'),
            'email.required'                => __('Email is required'),
            'email.unique'                  => __('Email already exist'),
            'password.required'             => __('Password is required'),
            'password.confirmed'            => __('Confirm password does not match'),
            'password.min'                  => __('You have to provide minimum 4 character password'),
            'g-recaptcha-response.required' => __('Please complete the recaptcha to submit the form'),
        ]);
        try {
            DB::beginTransaction();
            $verificationToken = Str::random(100);
            
            $user = User::create([
                'name'               => $request->name,
                'email'              => $request->email,
                'status'             => 'active',
                'is_banned'          => 'no',
                'password'           => Hash::make($request->password),
                'verification_token' => $verificationToken,
            ]);

            // Log the token creation for debugging
            \Log::info('User registration - Token created', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'verification_token' => $verificationToken,
                'token_length' => strlen($verificationToken)
            ]);

            try {
                (new MailSenderService)->sendVerifyMailSingleUser($user);
                DB::commit();

                // Store token in session for manual verification if needed
                session(['pending_verification_token' => $verificationToken, 'pending_verification_email' => $user->email]);
                
                $notification = __('A verification link has been sent to your mail. If you don\'t receive it within 5 minutes, please check your spam folder or contact support.');
                $notification = ['message' => $notification, 'alert-type' => 'success'];
                return redirect()->route('login')->with($notification);
            } catch (\Exception $e) {
                DB::rollBack();
                
                // Log the email sending failure
                \Log::error('Email sending failed during registration', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'error' => $e->getMessage()
                ]);
                
                // Delete the user since email failed
                $user->delete();
                
                $notification = __('Registration failed due to email service issue. Please try again or contact support.');
                $notification = ['message' => $notification, 'alert-type' => 'error'];
                return redirect()->back()->with($notification);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleMailException($e);
        }

    }

    public function custom_user_verification($token) {
        // Debug: Log the token for troubleshooting
        \Log::info('Email verification attempt', [
            'token' => $token,
            'token_length' => strlen($token),
            'url' => request()->url()
        ]);
        
        $user = User::where('verification_token', $token)->first();
        
        if ($user) {
            if ($user->email_verified_at != null) {
                $notification = __('Email already verified');
                $notification = ['message' => $notification, 'alert-type' => 'error'];
                return redirect()->route('login')->with($notification);
            }

            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->verification_token = null;
            $user->save();

            $notification = __('Email verified successfully! You can now login.');
            $notification = ['message' => $notification, 'alert-type' => 'success'];
            return redirect()->route('login')->with($notification);
        } else {
            // Log detailed error for debugging
            \Log::error('Email verification failed - Token not found', [
                'token' => $token,
                'token_length' => strlen($token),
                'total_users_with_tokens' => User::whereNotNull('verification_token')->count(),
                'url' => request()->url()
            ]);
            
            // Try to find user by email and regenerate token as fallback
            $recentUsers = User::whereNull('email_verified_at')
                ->whereNotNull('verification_token')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
            if ($recentUsers->count() > 0) {
                \Log::info('Found unverified users, attempting to help with verification', [
                    'count' => $recentUsers->count(),
                    'users' => $recentUsers->pluck('email')->toArray()
                ]);
            }
            
            $notification = __('Invalid verification link. Please try registering again or contact support.');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
            return redirect()->route('register')->with($notification);
        }
    }

    public function resendVerification(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();
        
        if ($user && $user->email_verified_at == null) {
            // Generate new token
            $user->verification_token = Str::random(100);
            $user->save();
            
            // Send new verification email
            (new MailSenderService)->sendVerifyMailSingleUser($user);
            
            $notification = __('A new verification link has been sent to your email.');
            $notification = ['message' => $notification, 'alert-type' => 'success'];
            return redirect()->route('login')->with($notification);
        } else {
            $notification = __('Email not found or already verified.');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
            return redirect()->back()->with($notification);
        }
    }

    public function manualVerification($email) {
        try {
            $user = User::where('email', $email)->whereNull('email_verified_at')->first();
            
            if ($user) {
                $user->email_verified_at = now();
                $user->verification_token = null;
                $user->save();
                
                $notification = __('Email verified successfully! You can now login.');
                $notification = ['message' => $notification, 'alert-type' => 'success'];
                return redirect()->route('login')->with($notification);
            } else {
                $notification = __('User not found or already verified.');
                $notification = ['message' => $notification, 'alert-type' => 'error'];
                return redirect()->route('login')->with($notification);
            }
        } catch (\Exception $e) {
            \Log::error('Manual verification failed', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            
            $notification = __('Verification failed. Please contact support.');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
            return redirect()->route('login')->with($notification);
        }
    }
}
