<?php

namespace App\Http\Controllers;

use App\Exceptions\SilaException;
use App\Exceptions\SilaTransactionCancellationException;
use App\Http\Requests\InitiateRemittanceRequest;
use App\Remittance;
use Domain\PaymentMethods\Actions\CancelSilaTransactionAction;
use Domain\Remittance\Actions\InitiateRemittanceAction;
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

    public function store(User $user, InitiateRemittanceRequest $initiateRemittanceRequest, InitiateRemittanceAction $initiateRemittanceAction): JsonResponse
    {
        try {
            $remittance = ($initiateRemittanceAction)($user, $initiateRemittanceRequest);

            return response()->json([
                'message' => 'Remittance in progress.',
                'data' => $remittance
            ], 201);
        } catch (SilaException $exception) {
            return response()->json([
                'message' => 'Unable to initiate remittance at this time.'
            ], 400);
        }
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
