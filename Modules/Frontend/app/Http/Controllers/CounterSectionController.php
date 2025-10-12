<?php

namespace Modules\Frontend\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Modules\Frontend\app\Http\Requests\CounterSectionRequest;
use Modules\Frontend\app\Models\Section;
use Modules\Frontend\app\Models\SectionTranslation;
use Modules\Frontend\app\Traits\UpdateSectionTraits;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class CounterSectionController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait, UpdateSectionTraits;

    /**
     * Display a listing of the resource.
     */
    public function index() {
        checkAdminHasPermissionAndThrowException('section.management');
        $code = request('code') ?? getSessionLanguage();
        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }
        $languages = allLanguages();
        $counterSection = Section::getByName('counter_section');

        return view('frontend::counter-section', compact('languages', 'code', 'counterSection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CounterSectionRequest $request) {
        checkAdminHasPermissionAndThrowException('section.management');
        $section = Section::getByName('counter_section');

        // Update global content
        $global_content = $this->updateSectionContent($section?->global_content, $request, ['year_experience_count','project_count','customer_count'], []);

        // Update translated content
        $content = $this->updateSectionContent($section?->content, $request, ['year_experience_title','year_experience_sub_title','project_title','project_sub_title','customer_title','customer_sub_title']);

        $section->update(['global_content' => $global_content]);

        $translation = SectionTranslation::where('section_id', $section->id)->exists();

        if (!$translation) {
            $this->generateTranslations(TranslationModels::Section, $section, 'section_id', $request);
        }

        $this->updateTranslations($section, $request, $request->only('code'), ['content' => $content]);

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }

}
