<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'receiving_account_id' => ['required', 'exists:bank_accounts,id'],
            'rate' => ['required'],
            'origin_currency' => ['required'],
            'destination_currency' => ['required'],
            'minimum_amount' => ['required'],
            'maximum_amount' => ['required']
        ];
    }
}
