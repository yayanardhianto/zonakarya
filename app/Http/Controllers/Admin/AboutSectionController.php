<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Frontend\app\Models\Section;
use Modules\Frontend\app\Models\Home;

class AboutSectionController extends Controller
{
    public function index()
    {
        $theme_name = DEFAULT_HOMEPAGE;
        
        // Get about page sections
        $sections = Section::whereHas("home", function ($q) use ($theme_name) {
            $q->where('slug', $theme_name);
        })->whereIn('name', [
            'counter_section',
            'choose_us_section',
            'award_section',
            'team_section',
            'contact_section',
            'brand_section'
        ])->ordered()->get();

        return view('admin.about-sections.index', compact('sections'));
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:sections,id',
            'sections.*.order' => 'required|integer|min:0'
        ]);

        foreach ($request->sections as $sectionData) {
            Section::where('id', $sectionData['id'])
                ->update(['order' => $sectionData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Section order updated successfully'
        ]);
    }

    public function toggleStatus(Request $request, Section $section)
    {
        $section->update([
            'is_active' => !$section->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Section status updated successfully',
            'is_active' => $section->is_active
        ]);
    }

    public function updateTitle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        try {
            // Update the about page title in settings using key-value structure
            \Modules\GlobalSetting\app\Models\Setting::updateOrCreate(
                ['key' => 'about_page_title'],
                ['value' => $request->title]
            );

            // Clear all relevant caches comprehensively
            \App\Helpers\CacheHelper::clearAllCaches();

            return response()->json([
                'success' => true,
                'message' => 'About page title updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update about page title: ' . $e->getMessage()
            ], 500);
        }
    }
}