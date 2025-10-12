<?php

namespace Modules\Frontend\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutSectionUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        if($this->code == 'en'){
            $rules = [
                'image' => ['nullable', 'image','mimes:jpeg,jpg,png,gif,webp,svg', 'max:2048'],
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:1000'],
                'button_text' => ['nullable', 'string', 'max:255'],
                'button_url' => ['nullable', 'max: 255'],
            ];
        }else {
            $rules = [
                'image' => ['nullable', 'image','mimes:jpeg,jpg,png,gif,webp,svg', 'max:2048'],
                'title' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:1000'],
                'button_text' => ['nullable', 'string', 'max:255'],
                'button_url' => ['nullable', 'max: 255'],
            ];
        }

        return $rules;
    }

    function messages() : Array
    {
        return [
            'image.nullable' => __('The image is not valid.'),
            'image.image' => __('The image is not valid.'),
            'image.max' => __('The image may not be greater than 2048 kilobytes.'),

            'title.nullable' => __('The title is not valid.'),
            'title.string' => __('The title is not valid.'),
            'title.max' => __('The title is too long.'),
            'description.nullable' => __('The description is not valid.'),
            'description.string' => __('The description is not valid.'),
            'description.max' => __('The description is too long.'),
            'button_text.nullable' => __('The button text is not valid.'),
            'button_text.string' => __('The button text is not valid.'),
            'button_text.max' => __('The button text is too long.'),
            'button_url.nullable' => __('The button url is not valid.'),
            'button_url.max' => __('The button url is too long.'),
        ];
    }
}
