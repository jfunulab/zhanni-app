<?php

namespace App\Http\Requests;

use App\Remittance;
use Domain\PaymentMethods\Models\Bank;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class InitiateRemittanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws AuthorizationException
     */
    public function authorize(): bool
    {
        $user = $this->route('user');

        $this->canUserRemit($user);

        return $user->id == auth()->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(array_values(Remittance::TYPE_MAPPING))],
            'reason' => ['string', 'required'],
            'amount' => ['required'],
//            'converted_amount' => ['required'],
            'rate' => ['required'],
            'funding_account_id' => ['required'],
            'recipient' => ['required'],
            'pickup_bank_id' => [
                'integer',
                Rule::requiredIf(function () {
                    return $this->type == Remittance::TYPE_MAPPING[Remittance::CASH_PICKUP];
                }),
                Rule::in(Bank::allowsCashPickup()->pluck('id')->toArray())
            ]
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Transfer was not successful',
            'errors' => $validator->errors()
        ], 422));
    }

    /**
     * @throws AuthorizationException
     */
    private function canUserRemit($user): void
    {
        $amountRemittedToday = $user->remittances()->whereDate('created_at', today())->sum('base_amount');
        $amountRemittedCurrentMonth = $user->remittances()->where('created_at', '>=', now()->subDays(30))->sum('base_amount');
        $amountToRemit = $this->get('amount');

        if ($amountToRemit > config('app.remittance_transaction_limit')) {
            throw new AuthorizationException('Exceeds transaction limit');
        }

        if (($amountRemittedToday + $amountToRemit) > config('app.daily_remittance_limit')) {
            throw new AuthorizationException('Exceeded daily limit');
        }

        if (($amountToRemit + $amountRemittedCurrentMonth) > config('app.monthly_remittance_limit')) {
            throw new AuthorizationException('Exceeded monthly limit');
        }
    }
}
