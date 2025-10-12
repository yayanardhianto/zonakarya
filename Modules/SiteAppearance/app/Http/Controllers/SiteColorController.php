<?php

namespace Modules\SiteAppearance\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\GlobalSetting\app\Models\Setting;

class SiteColorController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        checkAdminHasPermissionAndThrowException('appearance.management');
        return view('siteappearance::site-color.index');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request) {
        checkAdminHasPermissionAndThrowException('appearance.management');
        $request->validate([
            'primary_color'      => 'required',
            'secondary_color'    => 'required',

        ], [
            'primary_color.required'      => __('Primary color field is required'),
            'secondary_color.required'    => __('Secondary color field is required'),
        ]);

        Setting::where('key', 'primary_color')->update(['value' => $request?->primary_color]);
        Setting::where('key', 'secondary_color')->update(['value' => $request?->secondary_color]);

        cache()->forget('setting');

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }
}
