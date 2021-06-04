<?php

namespace App\Http\Controllers;

use App\Http\Requests\InitiateRemittanceRequest;
use App\Jobs\SendBankTransfer;
use Domain\Remittance\DTOs\RemittanceData;
use Domain\Users\Models\User;
use Laravel\Cashier\Exceptions\PaymentActionRequired;
use Laravel\Cashier\Exceptions\PaymentFailure;

class UserRemittancesController extends Controller
{

    public function index(User $user)
    {
        $remittances = $user->remittances()
            ->with(['creditPayment.source', 'debitPayment.recipient.bank', 'recipient.bank', 'recipient.user'])->get();

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
                'reason' => $remittanceData->reason,
                'base_currency' => $remittanceData->rate->base,
                'amount_to_remit' => $remittanceData->amount * $remittanceData->rate->rate,
                'currency_to_remit' => $remittanceData->rate->currency,
                'recipient_id' => $remittanceData->recipient->id
            ]);
            $remittance->creditPayment()->create([
                'source_id' => $remittanceData->card->id,
                'amount' => $remittanceData->amount,
                'currency' => $remittanceData->rate->base,
                'status' => 'paid'
            ]);

            SendBankTransfer::dispatch($remittance, $remittanceData->recipient);

            return response()->json([
                'message' => 'Remittance in progress.',
                'data' => $remittance->fresh(['creditPayment.source', 'debitPayment.recipient.bank', 'recipient.bank', 'recipient.user'])
            ], 201);
        } catch (PaymentActionRequired | PaymentFailure $e) {
            return response()->json([
            'message' => $e->getMessage()
            ], 500);
        }
    }
}
