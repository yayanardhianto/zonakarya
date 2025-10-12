<?php

namespace Modules\Frontend\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CounterSectionRequest extends FormRequest {
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array {

        $rules = [
            'year_experience_count'     => ['nullable', 'numeric', 'max:1000000000', 'min:0'],
            'year_experience_title'     => ['required', 'string', 'max:255'],
            'year_experience_sub_title' => ['nullable', 'string', 'max:255'],

            'project_count'             => ['nullable', 'numeric', 'max:1000000000', 'min:0'],
            'project_title'             => ['required', 'string', 'max:255'],
            'project_sub_title'         => ['nullable', 'string', 'max:255'],

            'customer_count'            => ['nullable', 'numeric', 'max:1000000000', 'min:0'],
            'customer_title'            => ['required', 'string', 'max:255'],
            'customer_sub_title'        => ['nullable', 'string', 'max:255'],

        ];

        return $rules;
    }

    function messages(): Array {
        return [
            'year_experience_count.max'          => __('Total year of experience count must be less than or equal to 1000000000'),
            'year_experience_count.min'          => __('Total year of experience count must be greater than or equal to 0'),
            'year_experience_title.required'     => __('The year of experience title is required'),
            'year_experience_title.string'       => __('The year of experience title must be a string'),
            'year_experience_title.max'          => __('The year of experience title must not be more than 255 characters'),
            'year_experience_sub_title.required' => __('The year of experience sub title is required'),
            'year_experience_sub_title.string'   => __('The year of experience sub title must be a string'),
            'year_experience_sub_title.max'      => __('The year of experience sub title must not be more than 255 characters'),

            'project_count.max'                  => __('Total project count must be less than or equal to 1000000000'),
            'project_count.min'                  => __('Total project count must be greater than or equal to 0'),
            'project_title.required'             => __('The project title is required'),
            'project_title.string'               => __('The project title must be a string'),
            'project_title.max'                  => __('The project title must not be more than 255 characters'),
            'project_sub_title.required'         => __('The project sub title is required'),
            'project_sub_title.string'           => __('The project sub title must be a string'),
            'project_sub_title.max'              => __('The project sub title must not be more than 255 characters'),

            'customer_count.max'                 => __('Total customer count must be less than or equal to 1000000000'),
            'customer_count.min'                 => __('Total customer count must be greater than or equal to 0'),
            'customer_title.required'            => __('The customer title is required'),
            'customer_title.string'              => __('The customer title must be a string'),
            'customer_title.max'                 => __('The customer title must not be more than 255 characters'),
            'customer_sub_title.required'        => __('The customer sub title is required'),
            'customer_sub_title.string'          => __('The customer sub title must be a string'),
            'customer_sub_title.max'             => __('The customer sub title must not be more than 255 characters'),

        ];
    }
}
