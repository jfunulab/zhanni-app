<?php

namespace App\Http\Controllers;

use App\Http\Requests\InitiateRemittanceRequest;
use App\Jobs\SendBankTransfer;
use Domain\Remittance\DTOs\RemittanceData;
use Domain\Users\Models\User;
use Laravel\Cashier\Exceptions\PaymentActionRequired;
use Laravel\Cashier\Exceptions\PaymentFailure;
use Support\PaymentGateway\DTOs\BankTransferData;

class UserRemittancesController extends Controller
{

    public function index(User $user)
    {
        $remittances = $user->remittances()->with(['creditPayment', 'debitPayment'])->get();

        return response()->json([
            'message' => 'User remittances',
            'data' => $remittances
        ]);
    }

    public function store(User $user, InitiateRemittanceRequest $initiateRemittanceRequest)
    {
        try {
            $remittanceData = RemittanceData::fromArray($initiateRemittanceRequest->toArray());
            $payment = $user->charge($remittanceData->amount * 100, $remittanceData->card->platform_id);
            $remittance = $user->remittances()->create([
                'base_amount' => $remittanceData->amount,
                'base_currency' => $remittanceData->rate->base,
                'amount_to_remit' => $remittanceData->amount * $remittanceData->rate->rate,
                'currency_to_remit' => $remittanceData->rate->currency
            ]);
            $remittance->creditPayment()->create([
                'source_id' => $remittanceData->card,
                'amount' => $remittanceData->amount,
                'currency' => $remittanceData->rate->base,
                'status' => 'paid'
            ]);

            SendBankTransfer::dispatch($remittance, BankTransferData::fromArray([
                'account_number' => $remittanceData->recipient->account_number,
                'bank_code' => $remittanceData->recipient->bank->code,
                'amount' => $remittanceData->amount * $remittanceData->rate->rate,
                'currency' => $remittanceData->rate->currency
            ]));

            return response()->json([
                'message' => 'Remittance in progress.',
                'data' => $remittance->fresh(['creditPayment', 'debitPayment'])
            ], 201);
        } catch (PaymentActionRequired | PaymentFailure $e) {
            return response()->json([
            'message' => $e->getMessage()
            ], 500);
        }
    }
}
