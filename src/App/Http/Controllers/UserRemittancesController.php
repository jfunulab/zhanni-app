<?php

namespace App\Http\Controllers;

use App\Exceptions\SilaTransactionCancellationException;
use App\Http\Requests\InitiateRemittanceRequest;
use App\Remittance;
use Domain\PaymentMethods\Actions\CancelSilaTransactionAction;
use Domain\PaymentMethods\Actions\IssueSilaAchDebitAction;
use Domain\PaymentMethods\DTOs\SilaDebitAchData;
use Domain\Remittance\DTOs\RemittanceData;
use Domain\Users\Models\User;
use Illuminate\Http\JsonResponse;

class UserRemittancesController extends Controller
{

    public function index(User $user): JsonResponse
    {
        $remittances = $user->remittances()
            ->with(['creditPayment.source', 'debitPayment.recipient.bank', 'recipient.bank', 'recipient.user'])->get();

        return response()->json([
            'message' => 'User remittances',
            'data' => $remittances
        ]);
    }

    public function store(User $user, InitiateRemittanceRequest $initiateRemittanceRequest, IssueSilaAchDebitAction $issueSilaAchDebitAction): JsonResponse
    {
        $remittanceData = RemittanceData::fromArray($initiateRemittanceRequest->toArray());
        $data = SilaDebitAchData::fromArray([
            'amount' => $remittanceData->amount,
            'price' => $remittanceData->price,
            'description' => $remittanceData->reason
        ]);
        $silaDebitResponse = ($issueSilaAchDebitAction)($remittanceData->fundingAccount, $data);

        if($silaDebitResponse->getStatusCode() == 200){
            $remittance = $user->remittances()->create([
                'base_amount' => $remittanceData->amount,
                'exchange_rate_id' => $remittanceData->rate->id,
                'reason' => $remittanceData->reason,
                'base_currency' => $remittanceData->rate->base,
                'amount_to_remit' => $remittanceData->amount * $remittanceData->rate->rate,
                'fee' => $remittanceData->price,
                'currency_to_remit' => $remittanceData->rate->currency,
                'funding_account_id' => $remittanceData->fundingAccount->id,
                'recipient_id' => $remittanceData->recipient->id
            ]);

            $remittanceData->fundingAccount->creditPayments()->create([
                'remittance_id' => $remittance->id,
                'reference_id' => $silaDebitResponse->getData()->getTransactionId(),
                'amount' => $remittanceData->totalAmount,
                'amount_in_cents' => $remittanceData->totalAmount * 100,
                'currency' => $remittanceData->rate->base,
                'status' => 'queued'
            ]);

            return response()->json([
                'message' => 'Remittance in progress.',
                'data' => $remittance->fresh(['creditPayment.sourceable', 'fundingAccount', 'debitPayment.recipient.bank', 'recipient.bank', 'recipient.user'])
            ], 201);
        }

        return response()->json([
            'message' => 'Unable to initiate remittance at this time.'
        ], 400);
    }

    public function cancel(User $user, Remittance $remittance, CancelSilaTransactionAction $cancelSilaTransactionAction): JsonResponse
    {
        try {
            ($cancelSilaTransactionAction)($remittance->creditPayment);
            return response()->json([
                'message' => 'Remittance cancelled'
            ]);
        } catch (SilaTransactionCancellationException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 403);
        }
    }
}
