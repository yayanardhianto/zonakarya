<?php

namespace Modules\Service\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\Service\app\Http\Requests\ServiceRequest;
use Modules\Service\app\Models\Service;

class ServiceController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        checkAdminHasPermissionAndThrowException('service.view');
        $query = Service::query();

        $query->when($request->filled('keyword'), function ($qa) use ($request) {
            $qa->whereHas('translations', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%');
                $q->orWhere('description', 'like', '%' . $request->keyword . '%');
                $q->orWhere('short_description', 'like', '%' . $request->keyword . '%');
            });
        });

        $query->when($request->filled('is_popular'), function ($q) use ($request) {
            $q->where('is_popular', $request->is_popular);
        });

        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $services = $request->get('par-page') == 'all' ? $query->with('translation')->orderBy('id', $orderBy)->get() : $query->with('translation')->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $services = $query->with('translation')->orderBy('id', $orderBy)->paginate(10)->withQueryString();
        }

        return view('service::index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        checkAdminHasPermissionAndThrowException('service.view');
        return view('service::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceRequest $request) {
        checkAdminHasPermissionAndThrowException('service.management');
        $service = Service::create(array_merge($request->validated()));

        if ($service && $request->hasFile('image')) {
            $file_name = file_upload($request->image, 'uploads/custom-images/', $service->image);
            $service->image = $file_name;
            $service->save();
        }
        if ($service && $request->hasFile('icon')) {
            $file_name = file_upload($request->icon, 'uploads/custom-images/', $service->icon);
            $service->icon = $file_name;
            $service->save();
        }

        $this->generateTranslations(
            TranslationModels::Service,
            $service,
            'service_id',
            $request,
        );

        return $this->redirectWithMessage(
            RedirectType::CREATE->value,
            'admin.service.edit',
            [
                'service' => $service->id,
                'code'    => allLanguages()->first()->code,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service) {
        checkAdminHasPermissionAndThrowException('service.view');
        $code = request('code') ?? getSessionLanguage();
        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }
        $languages = allLanguages();

        return view('service::edit', compact('service', 'code', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceRequest $request, $id): RedirectResponse {
        checkAdminHasPermissionAndThrowException('service.management');
        $validatedData = $request->validated();

        $service = Service::findOrFail($id);
        $service->update($request->except('image', 'icon'));

        if ($service && !empty($request->image)) {
            $file_name = file_upload($request->image, 'uploads/custom-images/', $service->image);
            $service->image = $file_name;
            $service->save();
        }
        if ($service && !empty($request->icon)) {
            $file_name = file_upload($request->icon, 'uploads/custom-images/', $service->icon);
            $service->icon = $file_name;
            $service->save();
        }

        $this->updateTranslations(
            $service,
            $request,
            $validatedData,
        );

        return $this->redirectWithMessage(RedirectType::UPDATE->value,'admin.service.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('service.management');

        $service = Service::findOrFail($id);

        $service->translations()->each(function ($translation) {
            if ($translation?->description) {
                deleteUnusedUploadedImages($translation?->description);
            }
            $translation->service()->dissociate();
            $translation->delete();
        });

        if ($service->image) {
            if (File::exists(public_path($service->image))) {
                unlink(public_path($service->image));
            }
        }
        if ($service->icon) {
            if (File::exists(public_path($service->icon))) {
                unlink(public_path($service->icon));
            }
        }

        $service->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.service.index');
    }

    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('service.management');

        $service = Service::find($id);
        $status = $service->status == 1 ? 0 : 1;
        $service->update(['status' => $status]);

        $notification = __('Updated Successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
