<?php

namespace Modules\Project\app\Http\Controllers;

use App\Enums\RedirectType;
use Illuminate\Http\Request;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Modules\Project\app\Models\Project;
use Modules\Service\app\Models\Service;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Project\app\Http\Requests\ProjectRequest;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class ProjectController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        checkAdminHasPermissionAndThrowException('project.view');
        $query = Project::query();

        $query->when($request->filled('keyword'), function ($qa) use ($request) {
            $keyword = '%' . $request->keyword . '%';
            $qa->where(function ($qa) use ($keyword) {
                $qa->whereHas('translations', function ($q) use ($keyword) {
                    $q->where('title', 'like', $keyword)
                      ->orWhere('description', 'like', $keyword)
                      ->orWhere('project_category', 'like', $keyword);
                })->orWhere('project_author', 'like', $keyword);
            });
        });

        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $projects = $request->get('par-page') == 'all' ? $query->with('translation')->orderBy('id', $orderBy)->get() : $query->with('translation')->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $projects = $query->with('translation')->orderBy('id', $orderBy)->paginate(10)->withQueryString();
        }

        return view('project::index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        checkAdminHasPermissionAndThrowException('project.view');
        $services = Service::active()->get();
        return view('project::create',compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request): RedirectResponse {
        checkAdminHasPermissionAndThrowException('project.management');
        $project = Project::create(array_merge($request->validated()));

        if ($project && $request->hasFile('image')) {
            $file_name = file_upload($request->image, 'uploads/custom-images/', $project->image);
            $project->image = $file_name;
            $project->save();
        }

        $this->generateTranslations(
            TranslationModels::Project,
            $project,
            'project_id',
            $request,
        );

        return $this->redirectWithMessage(
            RedirectType::CREATE->value,
            'admin.project.edit',
            [
                'project' => $project->id,
                'code'    => allLanguages()->first()->code,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {
        checkAdminHasPermissionAndThrowException('project.view');
        $code = request('code') ?? getSessionLanguage();
        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }
        $languages = allLanguages();
        $project = Project::findOrFail($id);
        $services = Service::get();

        return view('project::edit', compact('project', 'code', 'languages', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, $id): RedirectResponse {
        checkAdminHasPermissionAndThrowException('project.management');
        $validatedData = $request->validated();

        $project = Project::findOrFail($id);

        $project->project_date = $request->project_date ?? $project->project_date;
        $project->service_id = $request->service_id ?? $project->service_id;
        $project->project_author = $request->project_author ?? $project->project_author;
        $project->tags = $request->tags ?? $project->tags;

        if ($project && !empty($request->image)) {
            $file_name = file_upload($request->image, 'uploads/custom-images/', $project->image);
            $project->image = $file_name;
        }
        $project->save();

        $this->updateTranslations(
            $project,
            $request,
            $validatedData,
        );

        return $this->redirectWithMessage(RedirectType::UPDATE->value,'admin.project.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('project.management');

        $project = Project::findOrFail($id);

        if ($project->image) {
            if (File::exists(public_path($project->image))) {
                unlink(public_path($project->image));
            }
        }

        $project->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.project.index');
    }

    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('project.management');

        $project = Project::find($id);
        $status = $project->status == 1 ? 0 : 1;
        $project->update(['status' => $status]);

        $notification = __('Updated Successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
