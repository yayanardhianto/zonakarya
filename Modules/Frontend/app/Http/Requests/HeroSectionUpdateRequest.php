<?php

namespace Modules\Frontend\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HeroSectionUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'title'              => ['required', 'string', 'max:255'],
            'title_two'          => ['required', 'string', 'max:255'],
            'sub_title'          => ['nullable', 'string', 'max:255'],
            'action_button_text' => ['nullable', 'string', 'max:255'],
            'action_button_url'  => ['nullable', 'max:255'],
            'hero_year_text'     => ['nullable', 'max:255'],
            'hero_year_image'    => ['nullable', 'mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml', 'max:2048'],
            'total_customers'    => ['nullable', 'string', 'max:255'],
            'image_two'          => ['nullable', 'mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml', 'max:2048'],
            'image'              => ['nullable', 'mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml', 'max:2048'],
        ];

        if (DEFAULT_HOMEPAGE == 'four') {
            $rules['title_two'] = ['nullable'];
        }
        if (DEFAULT_HOMEPAGE == 'two') {
            $rules['title_three'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }

    function messages(): array
    {
        return [
            'title.required'            => __('The title is required'),
            'title.string'              => __('The title must be a string'),
            'title.max'                 => __('The title must not be more than 255 characters'),
            'title_two.required'        => __('The title Part two is required'),
            'title_two.string'          => __('The title Part two must be a string'),
            'title_two.max'             => __('The title Part two must not be more than 255 characters'),
            'title_three.sometimes'     => __('The title Part three is required'),
            'title_three.string'        => __('The title Part three must be a string'),
            'title_three.max'           => __('The title Part three must not be more than 255 characters'),
            'sub_title.required'        => __('The sub title is required'),
            'sub_title.string'          => __('The sub title must be a string'),
            'sub_title.max'             => __('The sub title must not be more than 255 characters'),
            'action_button_text.string' => __('The action button text must be a string'),
            'action_button_text.max'    => __('The action button text must not be more than 255 characters'),
            'action_button_url.max'     => __('The action button url must not be more than 255 characters'),
            'total_customers.max'       => __('Total Customer must not be more than 255 characters'),

            'hero_year_text.max'        => __('The hero year text must not be more than 255 characters'),
            'hero_year_image.image'     => __('The hero year image must be an image.'),
            'hero_year_image.max'       => __('The hero year image may not be greater than 2048 kilobytes.'),

            'image.image'               => __('The image must be an image.'),
            'image.max'                 => __('The image may not be greater than 2048 kilobytes.'),

            'image_two.image'           => __('Total Customer image must be an image.'),
            'image_two.max'             => __('Total Customer image may not be greater than 2048 kilobytes.'),

        ];
    }
}
