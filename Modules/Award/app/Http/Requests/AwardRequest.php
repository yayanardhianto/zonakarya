<?php

namespace Modules\Award\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AwardRequest extends FormRequest {
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array {
        return [
            'status'    => 'nullable',
            'url'       => 'nullable|max:255',
            'code'      => 'required|string|exists:languages,code',
            'year'     => 'required|string|max:50',
            'title'     => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            'tag'       => 'required|string|max:190',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    function messages(): array {
        return [
            'code.required'      => __('Language is required and must be a string.'),
            'code.exists'        => __('The selected language is invalid.'),
            'code.string'        => __('The language code must be a string.'),

            'year.required'       => __('The year is required.'),
            'year.string'         => __('The year must be a string.'),
            'year.max'            => __('The year must not exceed 50 characters.'),

            'title.required'     => __('The title is required.'),
            'title.string'       => __('The title must be a string.'),
            'title.max'          => __('The title must not be more than 255 characters'),

            'sub_title.required' => __('The sub title is required'),
            'sub_title.string'   => __('The sub title must be a string'),
            'sub_title.max'      => __('The sub title must not be more than 255 characters'),

            'tag.required'       => __('The tag is required.'),
            'tag.string'         => __('The tag must be a string.'),
            'tag.max'            => __('The tag must not exceed 190 characters.'),

        ];
    }
}
