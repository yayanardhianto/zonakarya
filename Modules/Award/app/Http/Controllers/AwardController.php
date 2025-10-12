<?php

namespace Modules\Award\app\Http\Controllers;

use App\Enums\RedirectType;
use Illuminate\Http\Request;
use App\Traits\RedirectHelperTrait;
use Modules\Award\app\Models\Award;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Language\app\Models\Language;
use Modules\Award\app\Http\Requests\AwardRequest;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class AwardController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index() {
        checkAdminHasPermissionAndThrowException('award.view');
        $awards = Award::latest()->paginate(5)->withQueryString();
        $code = request('code') ?? getSessionLanguage();
        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }
        $languages = allLanguages();
        return view('award::index', compact('awards', 'languages', 'code'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AwardRequest $request): RedirectResponse {
        checkAdminHasPermissionAndThrowException('award.management');
        $item = Award::create($request->validated());

        $this->generateTranslations(
            TranslationModels::Award,
            $item,
            'award_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AwardRequest $request, $id): RedirectResponse {
        checkAdminHasPermissionAndThrowException('award.management');
        $validatedData = $request->validated();

        $item = Award::findOrFail($id);
        $item->update($validatedData);

        $this->updateTranslations($item, $request, $validatedData);

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('award.management');

        $item = Award::findOrFail($id);

        $item->translations()->each(function ($translation) {
            $translation->award()->dissociate();
            $translation->delete();
        });

        $item->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }
    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('award.management');

        $item = Award::find($id);
        $status = $item->status == 1 ? 0 : 1;
        $item->update(['status' => $status]);

        $notification = __('Updated Successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
