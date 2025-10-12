<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class TinymceImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml|max:2048',
        ], [
            'file.required'           => __('Image is required'),
            'file.image'              => __('The image must be an image.'),
            'file.max'                => __('The image may not be greater than 2048 kilobytes.'),
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
        }
        $uploadPath = TINYMNCE_UPLOAD_PATH;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $url = file_upload($file, "uploads/{$uploadPath}/");

            return response()->json(['location' => asset($url)]);
        }

        return response()->json(['error' => __('Image upload failed')], 422);
    }

    public function destroy(Request $request)
    {
        $image_path = preg_replace('/^.*\/(uploads\/.*)$/', '$1', $request->input('file_path'));
        $fullPath = public_path($image_path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
            return response()->json(['success' => true]);
        }
        return response()->json(['error' => __('File not found or path missing')], 400);
    }
}
