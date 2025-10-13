<?php

namespace App\Http\Controllers\Frontend\User;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Modules\Location\app\Models\Country;

class ProfileController extends Controller
{
    use RedirectHelperTrait;
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $countries = Country::select('id')->with(['translation' => function ($query) {
            $query->select('country_id', 'name');
        }])->active()->orderBy('slug')->get();

        return view('frontend.profile.edit', ['user' => $request->user(), 'countries' => $countries]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = userAuth();

        $rules = [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'age'      => 'required',
            'phone'    => 'required',
            'gender'   => 'required',
            'zip_code' => 'required',
            'country_id' => 'required',
            'province' => 'required',
            'city'     => 'required',
            'address'  => 'required|max:220',
        ];
        $customMessages = [
            'name.required'     => __('Name is required'),
            'email.required'    => __('Email is required'),
            'email.unique'      => __('Email already exist'),
            'phone.required'    => __('Phone is required'),
            'age.required'      => __('Age is required'),
            'phone.regex'       => __('Enter a valid phone number'),
            'gender.required'   => __('Gender is required'),
            'zip_code.required' => __('Zip code is required'),
            'country_id.required' => __('Country is required'),
            'province.required' => __('Province is required'),
            'city.required'     => __('City is required'),
            'address.required'  => __('Address is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $old_email = $user->email;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->age = $request->age;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->zip_code = $request->zip_code;
        $user->country_id = $request->country_id;
        $user->province = $request->province;
        $user->city = $request->city;
        $user->address = $request->address;
        $user->save();

        if ($old_email != $user->email) {
            Auth::guard('web')->logout();
            return to_route('login');
        }

        $notification = __('Your profile updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('user.dashboard')->with($notification);
    }

    /**
     * Display the user's profile form.
     */
    public function change_password(Request $request): View
    {
        return view('frontend.profile.update_password', ['user' => $request->user()]);
    }

    public function update_password(Request $request)
    {
        $user = userAuth();
        
        // Check if user has social login credentials
        $hasSocialLogin = $user->socialite()->exists() || !empty($user->provider);
        
        if ($hasSocialLogin) {
            // For social login users, only require new password
            $rules = [
                'password' => 'required|min:4|confirmed',
            ];
            $customMessages = [
                'password.required'  => __('Password is required'),
                'password.min'       => __('Password minimum 4 character'),
                'password.confirmed' => __('Confirm password does not match'),
            ];
        } else {
            // For regular users, require current password
            $rules = [
                'current_password' => 'required',
                'password'         => 'required|min:4|confirmed',
            ];
            $customMessages = [
                'current_password.required' => __('Current password is required'),
                'password.required'         => __('Password is required'),
                'password.min'              => __('Password minimum 4 character'),
                'password.confirmed'        => __('Confirm password does not match'),
            ];
        }
        
        $this->validate($request, $rules, $customMessages);

        if ($hasSocialLogin) {
            // For social login users, just update the password
            $user->password = Hash::make($request->password);
            $user->save();

            $notification = __('Password set successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->route('user.dashboard')->with($notification);
        } else {
            // For regular users, verify current password first
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = Hash::make($request->password);
                $user->save();

                $notification = __('Password change successfully');
                $notification = ['message' => $notification, 'alert-type' => 'success'];

                return redirect()->route('user.dashboard')->with($notification);
            } else {
                $notification = __('Current password does not match');
                $notification = ['message' => $notification, 'alert-type' => 'error'];

                return redirect()->back()->with($notification);
            }
        }
    }

    public function update_image(Request $request)
    {
        $rules = ['image' => 'required|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml|max:2048'];
        $customMessages = [
            'image.required' => __('The image field is required.'),
            'image.image'    => __('The image must be an image.'),
            'image.max'      => __('The image may not be greater than 2048 kilobytes.'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user = userAuth();

        if ($user && $request->hasFile('image')) {
            $file_name = file_upload($request->image, 'uploads/custom-images/', $user->image);
            $user->image = $file_name;
            $user->save();
        }

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }
}
