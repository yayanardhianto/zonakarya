<?php

namespace Modules\Service\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check() ? true : false;
    }

    public function rules(): array
    {
        $rules = [
            'seo_title'         => 'nullable|string|max:1000',
            'seo_description'   => 'nullable|string|max:1000',
            'is_popular'        => 'nullable',
            'status'            => 'nullable',
            'btn_text'          => 'required|string',
            'description'       => 'required',
            'short_description' => 'required|string|max:500',
        ];

        if ($this->isMethod('put')) {
            $rules['code'] = 'required|exists:languages,code';
            $rules['title'] = 'required|string|max:255';
            $rules['image'] = 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml|max:2048';
            $rules['icon'] = 'sometimes|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml|max:2048';
        }
        if ($this->isMethod('post')) {
            $rules['image'] = 'required|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml';
            $rules['icon'] = 'required|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml|max:2048';
            $rules['slug'] = 'required|string|max:255|unique:services,slug';
            $rules['title'] = 'required|string|max:255|unique:service_translations,title';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [

            'code.required'            => __('Language is required and must be a string.'),
            'code.exists'              => __('The selected language is invalid.'),

            'image.required'           => __('Image is required'),
            'image.image'              => __('The image must be an image.'),
            'image.max'                => __('The image may not be greater than 2048 kilobytes.'),

            'icon.required'            => __('Icon is required.'),
            'icon.image'               => __('The IconIcon is must be an image file.'),
            'icon.max'                 => __('The Icon may not be greater than 512 kilobytes.'),


            'description.required'     => __('Description is required.'),

            'short_description.required'     => __('Short description is required.'),
            'short_description.string' => __('Short description must be a string.'),
            'short_description.max'        => __('Short may not be greater than 500 characters.'),

            'slug.required'        => __('Slug is required.'),
            'slug.string'          => __('The slug must be a string.'),
            'slug.max'             => __('The slug may not be greater than 255 characters.'),
            'slug.unique'          => __('The slug has already been taken.'),

            'btn_text.required'        => __('Button text is required.'),
            'btn_text.string'          => __('The Button text must be a string.'),

            'title.required'            => __('The title is required.'),
            'title.string'              => __('The title must be a string.'),
            'title.max'                 => __('The title may not be greater than 255 characters.'),
            'title.unique'              => __('Title must be unique.'),

            'seo_title.max'           => __('SEO title may not be greater than 1000 characters.'),
            'seo_title.string'        => __('SEO title must be a string.'),
            'seo_description.max'     => __('SEO description may not be greater than 2000 characters.'),
            'seo_description.string'  => __('SEO description must be a string.'),
        ];
    }
}
