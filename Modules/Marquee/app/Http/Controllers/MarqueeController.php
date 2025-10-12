<?php

namespace Modules\Marquee\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\RedirectResponse;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\Marquee\app\Http\Requests\NewsTickerRequest;
use Modules\Marquee\app\Models\NewsTicker;

class MarqueeController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index() {
        checkAdminHasPermissionAndThrowException('marquee.view');
        $marquees = NewsTicker::latest()->paginate(5)->withQueryString();
        $code = request('code') ?? getSessionLanguage();
        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }
        $languages = allLanguages();
        return view('marquee::index', compact('marquees', 'languages', 'code'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewsTickerRequest $request): RedirectResponse {
        checkAdminHasPermissionAndThrowException('marquee.management');
        $item = NewsTicker::create($request->validated());

        $this->generateTranslations(
            TranslationModels::NewsTicker,
            $item,
            'news_ticker_id',
            $request,
        );
        

        return $this->redirectWithMessage(RedirectType::CREATE->value);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NewsTickerRequest $request, $id): RedirectResponse {
        checkAdminHasPermissionAndThrowException('marquee.management');
        $validatedData = $request->validated();

        $item = NewsTicker::findOrFail($id);
        $item->update($validatedData);

        $this->updateTranslations($item, $request, $validatedData);
        

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('marquee.management');

        $item = NewsTicker::findOrFail($id);

        $item->translations()->each(function ($translation) {
            $translation->news_ticker()->dissociate();
            $translation->delete();
        });

        $item->delete();
        

        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }
    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('marquee.management');

        $item = NewsTicker::find($id);
        $status = $item->status == 1 ? 0 : 1;
        $item->update(['status' => $status]);

        $notification = __('Updated Successfully');
        

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
