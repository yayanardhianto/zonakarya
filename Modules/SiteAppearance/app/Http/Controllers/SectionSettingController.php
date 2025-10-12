<?php

namespace Modules\SiteAppearance\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SiteAppearance\app\Models\SectionSetting;

class SectionSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        checkAdminHasPermissionAndThrowException('appearance.management');
        $sectionSetting = SectionSetting::first();
        return view('siteappearance::section-setting.index', compact('sectionSetting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) 
    {
        checkAdminHasPermissionAndThrowException('appearance.management');

        SectionSetting::updateOrCreate(
            ['id' => 1],
            [
                'hero_section' => $request->has('hero_section'),
                'about_section' => $request->has('about_section'),
                'project_section' => $request->has('project_section'),
                'team_section' => $request->has('team_section'),
                'testimonial_section' => $request->has('testimonial_section'),
                'service_section' => $request->has('service_section'),
                'service_feature_section' => $request->has('service_feature_section'),
                'award_section' => $request->has('award_section'),
                'marquee_section' => $request->has('marquee_section'),
                'call_to_action_section' => $request->has('call_to_action_section'),
                'brands_section' => $request->has('brands_section'),
                'counter_section' => $request->has('counter_section'),
                'faq_section' => $request->has('faq_section'),
                'choose_us_section' => $request->has('choose_us_section'),
                'contact_us_section' => $request->has('contact_us_section'),
                'pricing_section' => $request->has('pricing_section'),
                'banner_section' => $request->has('banner_section'),
                'latest_blog_section' => $request->has('latest_blog_section'),
            ]
        );
        return redirect()->back()->with(['message' => __('Updated successfully'), 'alert-type' => 'success']);
    }
}
