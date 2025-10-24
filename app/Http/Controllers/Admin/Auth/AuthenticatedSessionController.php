<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\Admin;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class AuthenticatedSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('destroy');
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $customMessages = [
            'email.required' => __('Email is required'),
            'password.required' => __('Password is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $credential = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $admin = Admin::where('email', $request->email)->first();

        if ($admin) {
            if ($admin->status == 'active') {
                try {
                    // Check if password is in correct format first
                    if (!str_starts_with($admin->password, '$2y$') && !str_starts_with($admin->password, '$2a$') && !str_starts_with($admin->password, '$argon2')) {
                        \Log::error('Admin password not in bcrypt/argon format', [
                            'admin_id' => $admin->id,
                            'email' => $admin->email,
                            'password_length' => strlen($admin->password),
                            'password_start' => substr($admin->password, 0, 10)
                        ]);
                        
                        $notification = __('Password format error. Please contact administrator.');
                        $notification = ['message' => $notification, 'alert-type' => 'error'];
                        return redirect()->back()->with($notification);
                    }
                    
                    // Handle old bcrypt format ($2a$) by converting to new format ($2y$)
                    $passwordToCheck = $admin->password;
                    if (str_starts_with($admin->password, '$2a$')) {
                        $passwordToCheck = str_replace('$2a$', '$2y$', $admin->password);
                        \Log::info('Converting old bcrypt format to new format', [
                            'admin_id' => $admin->id,
                            'email' => $admin->email,
                            'old_format' => substr($admin->password, 0, 10),
                            'new_format' => substr($passwordToCheck, 0, 10)
                        ]);
                    }
                    
                    if (Hash::check($request->password, $passwordToCheck)) {
                        if (Auth::guard('admin')->attempt($credential, $request->remember)) {
                            $notification = __('Logged in successfully.');
                            $notification = ['message' => $notification, 'alert-type' => 'success'];

                            $intendedUrl = session()->get('url.intended');
                            if ($intendedUrl && Str::contains($intendedUrl, '/admin')) {
                                return redirect()->intended(route('admin.dashboard'))->with($notification);
                            }
                            return redirect()->route('admin.dashboard')->with($notification);
                        }
                    } else {
                        $notification = __('Invalid Password');
                        $notification = ['message' => $notification, 'alert-type' => 'error'];

                        return redirect()->back()->with($notification);
                    }
                } catch (\RuntimeException $e) {
                    \Log::error('Bcrypt algorithm error during admin login', [
                        'admin_id' => $admin->id,
                        'email' => $admin->email,
                        'error' => $e->getMessage(),
                        'password_length' => strlen($admin->password),
                        'password_start' => substr($admin->password, 0, 10)
                    ]);
                    
                    $notification = __('Password verification error. Please contact administrator.');
                    $notification = ['message' => $notification, 'alert-type' => 'error'];
                    return redirect()->back()->with($notification);
                } catch (\Exception $e) {
                    \Log::error('Unexpected error during admin login', [
                        'admin_id' => $admin->id,
                        'email' => $admin->email,
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
            $notification = __('Invalid Email');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $notification = __('Logged out successfully.');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.login')->with($notification);
    }
}
