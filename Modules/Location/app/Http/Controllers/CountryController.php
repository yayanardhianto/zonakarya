<?php

namespace Modules\Location\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\Location\app\Models\Country;

class CountryController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        checkAdminHasPermissionAndThrowException('country.view');

        $query = Country::query();

        $query->when($request->filled('keyword'), function ($qa) use ($request) {
            $qa->whereHas('translations', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%');
            });
        });

        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 0 ? 'desc' : 'asc';

        if ($request->filled('par-page')) {
            $countries = $request->get('par-page') == 'all' ? $query->with('translation')->orderBy('id', $orderBy)->get() : $query->with('translation')->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $countries = $query->with('translation')->orderBy('slug', $orderBy)->paginate(15)->withQueryString();
        }

        $code = request('code') ?? getSessionLanguage();
        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }
        $languages = allLanguages();
        return view('location::country', compact('countries', 'languages', 'code'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse {
        checkAdminHasPermissionAndThrowException('country.management');
        $request->validate([
            'status' => 'nullable',
            'code'   => 'required|string|exists:languages,code',
            'name'   => 'required|string|max:255',
            'slug'   => 'required|string|max:255|unique:countries,slug',
        ], [
            'code.required' => __('Language is required and must be a string.'),
            'code.exists'   => __('The selected language is invalid.'),

            'code.string'   => __('The language code must be a string.'),

            'name.required' => __('Name is required'),
            'name.string'   => __('The name must be a string.'),
            'name.max'      => __('The name may not be greater than 255 characters.'),

            'slug.required' => __('Slug is required and must be a unique string with a maximum length of 255 characters.'),
            'slug.max'      => __('Slug is required and must be a unique string with a maximum length of 255 characters.'),
            'slug.unique'   => __('Slug must be unique.'),

        ]);
        $item = Country::create($request->all());

        $this->generateTranslations(
            TranslationModels::Country,
            $item,
            'country_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.country.index', ['code' => $request->code]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse {
        checkAdminHasPermissionAndThrowException('country.management');
        $request->validate([
            'status' => 'nullable',
            'code'   => 'required|string|exists:languages,code',
            'name'   => 'required|string|max:255',
            'slug'   => 'sometimes|string|max:255|unique:countries,slug,' . $id,
        ], [
            'code.required' => __('Language is required and must be a string.'),
            'code.exists'   => __('The selected language is invalid.'),

            'code.string'   => __('The language code must be a string.'),

            'name.required' => __('Name is required'),
            'name.string'   => __('The name must be a string.'),
            'name.max'      => __('The name may not be greater than 255 characters.'),

            'slug.required' => __('Slug is required and must be a unique string with a maximum length of 255 characters.'),
            'slug.max'      => __('Slug is required and must be a unique string with a maximum length of 255 characters.'),
            'slug.unique'   => __('Slug must be unique.'),

        ]);
        $validatedData = $request->all();

        $item = Country::findOrFail($id);
        $item->update($validatedData);

        $this->updateTranslations($item, $request, $validatedData);

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.country.index', ['code' => $request->code]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('country.management');

        $item = Country::findOrFail($id);

        if ($item->users()->exists() || $item->delivery_addresses()->exists()) {
            return redirect()->back()->with(['alert-type' => 'error', 'message' => __('Unable to delete as relational data exists!')]);
        }

        $item->translations()->each(function ($translation) {
            $translation->country()->dissociate();
            $translation->delete();
        });

        $item->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }
    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('country.management');

        $item = Country::find($id);
        $status = $item->status == 1 ? 0 : 1;
        $item->update(['status' => $status]);

        $notification = __('Updated Successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
