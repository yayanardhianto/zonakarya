<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Traits\GetGlobalInformationTrait;
use App\Traits\GlobalMailTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller {
    use GetGlobalInformationTrait, GlobalMailTrait;

    /**
     * Display the password reset link request view.
     */
    public function create(): View {
        return view('admin.auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function custom_forget_password(Request $request) {

        $setting = Cache::get('setting');

        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => __('Email is required'),
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if ($admin) {
            $admin->forget_password_token = Str::random(100);
            $admin->save();

            [$subject, $message] = $this->fetchEmailTemplate('password_reset', ['user_name' => $admin->name]);
            $link = [__('CONFIRM YOUR EMAIL') => route('admin.password.reset', $admin->forget_password_token)];

            try {
                $this->sendMail($admin->email, $subject, $message, $link);
            } catch (\Exception $e) {
                info($e->getMessage());
            }


            $notification = __('A password reset link has been send to your mail');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->back()->with($notification);
        } else {
            $notification = __('Email does not exist');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }
    }
}
