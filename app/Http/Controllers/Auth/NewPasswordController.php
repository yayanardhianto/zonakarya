<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\CustomRecaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class NewPasswordController extends Controller {
    public function custom_reset_password_page(Request $request, $token) {
        $user = User::select('id', 'name', 'email', 'forget_password_token')->where('forget_password_token', $token)->first();
        if (!$user) {
            $notification = __('Invalid token, please try again');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('password.request')->with($notification);
        }
        return view('frontend.auth.reset-password', ['user' => $user, 'token' => $token]);
    }

    public function custom_reset_password_store(Request $request, $token) {
        $setting = Cache::get('setting');

        $rules = [
            'email'                => 'required',
            'password'             => 'required|min:4|confirmed',
            'g-recaptcha-response' => $setting->recaptcha_status == 'active' ? ['required', new CustomRecaptcha()] : '',
        ];
        $customMessages = [
            'email.required'                => __('Email is required'),
            'password.required'             => __('Password is required'),
            'password.confirmed'            => __('Confirm password does not match'),
            'password.min'                  => __('You have to provide minimum 4 character password'),
            'g-recaptcha-response.required' => __('Please complete the recaptcha to submit the form'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user = User::select('id', 'name', 'email', 'forget_password_token')->where('forget_password_token', $token)->where('email', $request->email)->first();

        if (!$user) {
            $notification = __('Invalid token, please try again');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }

        $user->password = Hash::make($request->password);
        $user->forget_password_token = null;
        $user->save();

        $notification = __('Password Reset successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('login')->with($notification);

    }
}
