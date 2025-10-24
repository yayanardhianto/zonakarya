<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\CustomRecaptcha;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller {
    /**
     * Display the login view.
     */
    public function create(): View {
        return view('frontend.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request) {
        $setting = Cache::get('setting');

        $rules = [
            'email'                => 'required|email',
            'password'             => 'required',
            'g-recaptcha-response' => $setting->recaptcha_status == 'active' ? ['required', new CustomRecaptcha()] : '',
        ];

        $customMessages = [
            'email.required'                => __('Email is required'),
            'password.required'             => __('Password is required'),
            'g-recaptcha-response.required' => __('Please complete the recaptcha to submit the form'),
        ];
        $this->validate($request, $rules, $customMessages);

        $credential = [
            'email'    => $request->email,
            'password' => $request->password,
        ];

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if ($user->status == UserStatus::ACTIVE->value) {
                if ($user->is_banned == UserStatus::UNBANNED->value) {
                    if ($user->email_verified_at == null) {
                        $notification = __('Please verify your email');
                        $notification = ['message' => $notification, 'alert-type' => 'error'];

                        return redirect()->back()->with($notification);
                    }

                    try {
                        // Handle old bcrypt format ($2a$) by converting to new format ($2y$)
                        $passwordToCheck = $user->password;
                        if (str_starts_with($user->password, '$2a$')) {
                            $passwordToCheck = str_replace('$2a$', '$2y$', $user->password);
                            \Log::info('Converting old bcrypt format to new format for user', [
                                'user_id' => $user->id,
                                'email' => $user->email,
                                'old_format' => substr($user->password, 0, 10),
                                'new_format' => substr($passwordToCheck, 0, 10)
                            ]);
                        }
                        
                        if (Hash::check($request->password, $passwordToCheck)) {
                            if (Auth::guard('web')->attempt($credential, $request->remember)) {

                                $notification = __('Logged in successfully.');
                                $notification = ['message' => $notification, 'alert-type' => 'success'];

                                $intendedUrl = session()->get('url.intended');
                                if ($intendedUrl && Str::contains($intendedUrl, '/admin')) {
                                    return redirect()->route('dashboard');
                                }
                                return redirect()->intended(route('dashboard'))->with($notification);
                            }
                        } else {
                            $notification = __('Invalid Password');
                            $notification = ['message' => $notification, 'alert-type' => 'error'];

                            return redirect()->back()->with($notification);
                        }
                    } catch (\RuntimeException $e) {
                        \Log::error('Bcrypt algorithm error during user login', [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'error' => $e->getMessage(),
                            'password_length' => strlen($user->password),
                            'password_start' => substr($user->password, 0, 10)
                        ]);
                        
                        $notification = __('Password verification error. Please contact administrator.');
                        $notification = ['message' => $notification, 'alert-type' => 'error'];
                        return redirect()->back()->with($notification);
                    } catch (\Exception $e) {
                        \Log::error('Unexpected error during user login', [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'error' => $e->getMessage()
                        ]);
                        
                        $notification = __('Login error. Please try again.');
                        $notification = ['message' => $notification, 'alert-type' => 'error'];
                        return redirect()->back()->with($notification);
                    }
                } else {
                    $notification = __('Inactive account');
                    $notification = ['message' => $notification, 'alert-type' => 'error'];

                    return redirect()->back()->with($notification);
                }
            } else {
                $notification = __('Inactive account');
                $notification = ['message' => $notification, 'alert-type' => 'error'];

                return redirect()->back()->with($notification);
            }
        } else {
            $notification = __('Invalid Email');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse {
        Auth::guard('web')->logout();
        $notification = __('Logged out successfully.');
        $notification = ['message' => $notification, 'alert-type' => 'success'];
        return redirect()->route('login')->with($notification);
    }
}
