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
                if (Hash::check($request->password, $admin->password)) {
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
