<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRemittanceRequest extends FormRequest
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
            'reason' => ['string'],
//            'amount' => ['required'],
            'rate' => ['integer', 'exists:exchange_rates,id'],
            'funding_account_id' => ['integer', 'exists:bank_accounts,id'],
            'recipient' => ['integer', 'exists:transfer_recipients,id'],
        ];
    }
}
