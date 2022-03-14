<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class InitiateRemittanceRequest extends FormRequest
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
            'amount' => ['required'],
//            'converted_amount' => ['required'],
            'rate' => ['required'],
            'funding_account_id' => ['required'],
            'recipient' => ['required'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Transfer was not successful',
            'errors' => $validator->errors()
        ], 422));
    }
}
