<?php

namespace Modules\Project\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProjectRequest extends FormRequest {
    /**
     * Get the validation rules that apply to the request.
     */
    public function authorize(): bool {
        return Auth::guard('admin')->check() ? true : false;
    }

    public function rules(): array {
        $rules = [
            'service_id'       => 'sometimes|exists:services,id',
            'description'      => 'required',
            'project_category' => 'required|string|max:255',
            'project_author'   => 'required|string|max:255',
            'seo_title'        => 'nullable|string|max:1000',
            'seo_description'  => 'nullable|string|max:1000',
            'project_date'     => 'required',
            'status'           => 'nullable',
        ];

        if ($this->isMethod('put')) {
            $rules['code'] = 'required|exists:languages,code';
            $rules['title'] = 'required|string|max:255';
            $rules['image'] = 'nullable|mimes:jpeg,jpg,png,gif,webp,svg';
            $rules['tags'] = 'nullable|string|max:255';
        }
        if ($this->isMethod('post')) {
            $rules['image'] = 'required|mimes:jpeg,jpg,png,gif,webp,svg';
            $rules['slug'] = 'required|string|max:255|unique:projects,slug';
            $rules['title'] = 'required|string|max:255|unique:project_translations,title';
            $rules['tags'] = 'nullable|string|max:255';
        }

        return $rules;
    }

    public function messages(): array {
        return [
            'code.required'             => __('Language is required and must be a string.'),
            'code.exists'               => __('The selected language is invalid.'),

            'service_id.required'       => __('The service is required.'),
            'service_id.exists'         => __('The selected service is invalid.'),

            'tags.max'                  => __('Tags may not be greater than 255 characters.'),
            'tags.string'               => __('Tags must be a string.'),

            'image.required'            => __('Image is required'),
            'image.image'               => __('The image must be an image.'),
            'image.max'                 => __('The image may not be greater than 2048 kilobytes.'),

            'title.required'            => __('The title is required.'),
            'title.string'              => __('The title must be a string.'),
            'title.max'                 => __('The title may not be greater than 255 characters.'),
            'title.unique'              => __('Title must be unique.'),

            'description.required'      => __('Description is required.'),

            'project_category.required' => __('Project Category is required.'),
            'project_author.required'   => __('Project Author is required.'),
            'project_date.required'     => __('Project Date is required.'),


            'slug.required'        => __('Slug is required.'),
            'slug.string'          => __('The slug must be a string.'),
            'slug.max'             => __('The slug may not be greater than 255 characters.'),
            'slug.unique'          => __('The slug has already been taken.'),

            'seo_title.max'           => __('SEO title may not be greater than 1000 characters.'),
            'seo_title.string'        => __('SEO title must be a string.'),
            'seo_description.max'     => __('SEO description may not be greater than 2000 characters.'),
            'seo_description.string'  => __('SEO description must be a string.'),
        ];
    }
}
