<?php

namespace Modules\Frontend\app\Http\Controllers;

use App\Enums\RedirectType;
use Illuminate\Http\Request;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Modules\Frontend\app\Models\Section;
use Modules\Frontend\app\Traits\UpdateSectionTraits;

class BannerSectionController extends Controller {
    use RedirectHelperTrait, UpdateSectionTraits;
    /**
     * Display a listing of the resource.
     */
    public function index() {
        checkAdminHasPermissionAndThrowException('section.management');
        $bannerSection = Section::getByName('banner_section');
        return view('frontend::banner-section', compact('bannerSection'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request) {
        checkAdminHasPermissionAndThrowException('section.management');
        $request->validate([
            'image'         => ['nullable', 'image','mimes:jpeg,jpg,png,gif,webp,svg', 'max:2048'],
            'video_url'         => ['required'],
        ], [
            'image.required' => __('The image is required.'),
            'image.image'    => __('The image is not valid.'),
            'image.max'      => __('The image is too large.'),
            'video_url.required' => __('The video url is required.'),
            'video_url.max' => __('The video url is too long.'),
        ]);
        $section = Section::getByName('banner_section');
        // Update global content
        $global_content = $this->updateSectionContent($section?->global_content, $request, ['video_url'], ['image']);

        $section->update(['global_content' => $global_content]);

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }
}
