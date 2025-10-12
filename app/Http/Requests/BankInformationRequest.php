<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankInformationRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'bank_name'      => 'required|string|max:190',
            'account_number' => 'required|string|max:190',
            'routing_number' => 'nullable|string|max:190',
            'branch'         => 'required|string|max:190',
            'transaction'    => 'required|string',
        ];
    }

    public function messages() {
        return [
            'bank_name.required'      => __('Bank Name is required.'),
            'account_number.required' => __('Account Number is required.'),
            'routing_number.required' => __('Routing Number is required.'),
            'branch.required'         => __('Branch is required.'),
            'transaction.required'    => __('Transaction is required.'),
        ];
    }
}
