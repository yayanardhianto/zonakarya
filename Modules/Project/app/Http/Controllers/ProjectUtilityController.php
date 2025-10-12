<?php

namespace Modules\Project\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Models\ProjectImage;

class ProjectUtilityController extends Controller {
    use RedirectHelperTrait;
    public function showGallery($id) {
        checkAdminHasPermissionAndThrowException('project.view');
        $gallery = ProjectImage::where('project_id', $id)->get();
        $project = Project::findOrFail($id);
        if (!$project) {
            abort(404);
        }

        return view('project::utilities.gallery', compact('project', 'gallery'));
    }

    public function updateGallery(Request $request, $id) {
        checkAdminHasPermissionAndThrowException('project.management');
        foreach ($request->file as $image) {
            $file_name = file_upload($image, 'uploads/custom-images/');

            $projectImage = new ProjectImage();
            $projectImage->project_id = $id;
            $projectImage->large_image = $file_name;
            $projectImage->small_image = $file_name;
            $projectImage->save();
        }
        if ($projectImage) {
            return response()->json([
                'message' => __('Images Saved Successfully'),
                'url'     => route('admin.project.gallery', $id),
            ]);
        } else {
            return $this->redirectWithMessage(RedirectType::ERROR->value);
        }
    }

    public function deleteGallery($id) {
        checkAdminHasPermissionAndThrowException('project.management');
        $projectImage = ProjectImage::findOrFail($id);

        if ($projectImage->large_image && !str($projectImage->large_image)->contains('website/images')) {
            if (@File::exists(public_path($projectImage->large_image))) {
                @unlink(public_path($projectImage->large_image));
            }
        }
        if ($projectImage->small_image && !str($projectImage->small_image)->contains('website/images')) {
            if (@File::exists(public_path($projectImage->small_image))) {
                @unlink(public_path($projectImage->small_image));
            }
        }

        $projectImage->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }

}
