<?php

namespace Modules\Frontend\app\Http\Controllers;

use App\Enums\ThemeList;
use App\Enums\RedirectType;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Modules\Frontend\app\Models\Section;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Frontend\app\Models\SectionTranslation;
use Modules\Frontend\app\Traits\UpdateSectionTraits;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\Frontend\app\Http\Requests\ServiceFeatureSectionRequest;

class ServiceFeatureSectionController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait, UpdateSectionTraits;

    /**
     * Display a listing of the resource.
     */
    public function index() {
        checkAdminHasPermissionAndThrowException('section.management');
        $code = request('code') ?? getSessionLanguage();
        if (DEFAULT_HOMEPAGE != ThemeList::TWO->value || !Language::where('code', $code)->exists()) {
            abort(404);
        }
        $languages = allLanguages();
        $featureSection = Section::getByName('service_feature_section');

        return view('frontend::' . DEFAULT_HOMEPAGE . '.service-features-section', compact('languages', 'code', 'featureSection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceFeatureSectionRequest $request) {
        checkAdminHasPermissionAndThrowException('section.management');
        $section = Section::getByName('service_feature_section');

        // Update global content
        $global_content = $this->updateSectionContent($section?->global_content, $request, ['skill_percentage_one','skill_percentage_two','skill_percentage_three','skill_percentage_four'], ['image']);

        // Update translated content
        $content = $this->updateSectionContent($section?->content, $request, ['title', 'sub_title', 'skill_title_one', 'skill_title_two', 'skill_title_three', 'skill_title_four']);

        $section->update(['global_content' => $global_content]);

        $translation = SectionTranslation::where('section_id', $section->id)->exists();

        if (!$translation) {
            $this->generateTranslations(TranslationModels::Section, $section, 'section_id', $request);
        }

        $this->updateTranslations($section, $request, $request->only('code'), ['content' => $content]);

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }
}
