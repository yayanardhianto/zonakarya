<?php

namespace Modules\OurTeam\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Modules\GlobalSetting\app\Models\Setting;
use Modules\OurTeam\app\Models\OurTeam;

class OurTeamController extends Controller
{
    public function index()
    {
        checkAdminHasPermissionAndThrowException('team.management');
        $teams = OurTeam::all();

        return view('ourteam::index', compact('teams'));
    }

    public function create()
    {
        checkAdminHasPermissionAndThrowException('team.management');
        return view('ourteam::create');
    }

    public function store(Request $request)
    {
        checkAdminHasPermissionAndThrowException('team.management');
        $rules = [
            'name'        => 'required',
            'slug'        => 'required|string|max:255|unique:our_teams,slug',
            'email'       => 'required',
            'designation' => 'required',
            'image'       => 'required|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml',
            'status'      => 'required',
        ];
        $customMessages = [
            'name.required'        => __('Name is required'),
            'email.required'       => __('Email is required'),
            'designation.required' => __('Designation is required'),
            'image.required'           => __('Image is required'),
            'image.image'              => __('The image must be an image.'),
            'image.max'                => __('The image may not be greater than 2048 kilobytes.'),
            'slug.required'             => __('Slug is required.'),
            'slug.string'               => __('The slug must be a string.'),
            'slug.max'                  => __('The slug may not be greater than 255 characters.'),
            'slug.unique'               => __('The slug has already been taken.'),
        ];
        $this->validate($request, $rules, $customMessages);

        $team = new OurTeam();

        $file_name = '';
        if ($request->file('image')) {
            $file_name = file_upload($request->image);
        }

        $team->name = $request->name;
        $team->slug = $request->slug;
        $team->email = $request->email;
        $team->phone = $request->phone;
        $team->designation = $request->designation;
        $team->image = $file_name;
        $team->sort_description = $request->sort_description;
        $team->status = $request->status;
        $team->facebook = $request->facebook;
        $team->twitter = $request->twitter;
        $team->dribbble = $request->dribbble;
        $team->instagram = $request->instagram;
        $team->save();

        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.ourteam.index')->with($notification);
    }

    public function edit($id)
    {
        $team = OurTeam::find($id);

        return view('ourteam::edit', compact('team'));
    }

    public function update(Request $request, $id)
    {
        checkAdminHasPermissionAndThrowException('team.management');
        $rules = [
            'name'        => 'required',
            'slug'        => 'required|string|max:255|unique:our_teams,slug,' . $id,
            'email'       => 'required',
            'designation' => 'required',
            'status'      => 'required',
            'image'       => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml|max:2048',
        ];
        $customMessages = [
            'name.required'        => __('Name is required'),
            'email.required'       => __('Email is required'),
            'designation.required' => __('Designation is required'),
            'image.image'              => __('The image must be an image.'),
            'image.max'                => __('The image may not be greater than 2048 kilobytes.'),
            'slug.required'             => __('Slug is required.'),
            'slug.string'               => __('The slug must be a string.'),
            'slug.max'                  => __('The slug may not be greater than 255 characters.'),
            'slug.unique'               => __('The slug has already been taken.'),
        ];
        $this->validate($request, $rules, $customMessages);

        $team = OurTeam::find($id);
        $team->name = $request->name;
        $team->slug = $request->slug;
        $team->email = $request->email;
        $team->phone = $request->phone;
        $team->designation = $request->designation;
        $team->sort_description = $request->sort_description;
        $team->status = $request->status;
        $team->facebook = $request->facebook;
        $team->twitter = $request->twitter;
        $team->dribbble = $request->dribbble;
        $team->instagram = $request->instagram;
        $team->save();

        if ($request->file('image')) {
            $file_name = file_upload($request->image, 'uploads/custom-images/', $team->image);
            $team->image = $file_name;
            $team->save();
        }

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.ourteam.index')->with($notification);
    }

    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('team.management');
        $team = OurTeam::find($id);
        $existing_image = $team->image;
        $team->delete();

        if ($existing_image) {
            if (File::exists(public_path($existing_image))) {
                unlink(public_path($existing_image));
            }
        }

        $notification = __('Delete Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.ourteam.index')->with($notification);
    }

    public function contactOurTeam(Request $request)
    {
        checkAdminHasPermissionAndThrowException('team.management');

        $request->validate([
            'contact_team_member' => 'required|in:active,inactive',
        ], [
            'contact_team_member.required' => __('Contact Team Member is required'),
            'contact_team_member.in'       => __('Contact Team Member is invalid'),
        ]);

        Setting::where('key', 'contact_team_member')->update(['value' => $request->contact_team_member]);

        Cache::forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }
}
