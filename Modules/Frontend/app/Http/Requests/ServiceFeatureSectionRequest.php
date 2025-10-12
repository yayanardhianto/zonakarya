<?php

namespace Modules\Frontend\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceFeatureSectionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        if ($this->code == 'en') {
            $rules = [
                'title'                  => ['required', 'string', 'max:255'],
                'sub_title'              => ['nullable', 'string', 'max:255'],
                'image'                  => ['nullable', 'mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml', 'max:2048'],

                'skill_percentage_one'   => ['required', 'numeric', 'max:100'],
                'skill_title_one'        => ['required', 'string', 'max:255'],
                'skill_percentage_two'   => ['required', 'numeric', 'max:100'],
                'skill_title_two'        => ['required', 'string', 'max:255'],
                'skill_percentage_three' => ['required', 'numeric', 'max:100'],
                'skill_title_three'      => ['required', 'string', 'max:255'],
                'skill_percentage_four'  => ['required', 'numeric', 'max:100'],
                'skill_title_four'       => ['required', 'string', 'max:255'],
            ];
        } else {
            $rules = [
                'title'                  => ['required', 'string', 'max:255'],
                'sub_title'              => ['nullable', 'string', 'max:255'],
                'image'                  => ['nullable', 'mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml', 'max:2048'],

                'skill_title_one'        => ['required', 'string', 'max:255'],
                'skill_title_two'        => ['required', 'string', 'max:255'],
                'skill_title_three'      => ['required', 'string', 'max:255'],
                'skill_title_four'       => ['required', 'string', 'max:255'],
            ];
        }

        return $rules;
    }

    function messages(): array
    {
        return [
            'title.required'                  => __('The title is required'),
            'title.string'                    => __('The title must be a string'),
            'title.max'                       => __('The title must not be more than 255 characters'),
            'sub_title.required'              => __('The sub title is required'),
            'sub_title.string'                => __('The sub title must be a string'),
            'sub_title.max'                   => __('The sub title must not be more than 255 characters'),

            'image.image'                     => __('The image must be an image.'),
            'image.max'                       => __('The image may not be greater than 2048 kilobytes.'),

            'skill_title_one.required'        => __('The skill one title field is required.'),
            'skill_title_one.string'          => __('The skill one title must be a string.'),
            'skill_title_one.max'             => __('The skill one title may not be greater than 255 characters.'),
            'skill_percentage_one.required'   => __('The skill one percentage field is required.'),
            'skill_percentage_one.numeric'    => __('The skill one percentage must be a number.'),
            'skill_percentage_one.max'        => __('The skill one percentage may not be greater than 100.'),

            'skill_title_two.required'        => __('The skill two title field is required.'),
            'skill_title_two.string'          => __('The skill two title must be a string.'),
            'skill_title_two.max'             => __('The skill two title may not be greater than 255 characters.'),
            'skill_percentage_two.required'   => __('The skill two percentage field is required.'),
            'skill_percentage_two.numeric'    => __('The skill two percentage must be a number.'),
            'skill_percentage_two.max'        => __('The skill two percentage may not be greater than 100.'),

            'skill_title_three.required'      => __('The skill three title field is required.'),
            'skill_title_three.string'        => __('The skill three title must be a string.'),
            'skill_title_three.max'           => __('The skill three title may not be greater than 255 characters.'),
            'skill_percentage_three.required' => __('The skill three percentage field is required.'),
            'skill_percentage_three.numeric'  => __('The skill three percentage must be a number.'),
            'skill_percentage_three.max'      => __('The skill three percentage may not be greater than 100.'),

            'skill_title_four.required'       => __('The skill four title field is required.'),
            'skill_title_four.string'         => __('The skill four title must be a string.'),
            'skill_title_four.max'            => __('The skill four title may not be greater than 255 characters.'),
            'skill_percentage_four.required'  => __('The skill four percentage field is required.'),
            'skill_percentage_four.numeric'   => __('The skill four percentage must be a number.'),
            'skill_percentage_four.max'       => __('The skill four percentage may not be greater than 100.'),

        ];
    }
}
