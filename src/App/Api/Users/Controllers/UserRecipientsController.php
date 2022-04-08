<?php

namespace App\Api\Users\Controllers;

use App\Http\Requests\AddRecipientToAccountRequest;
use App\Http\Requests\UpdateTransferRecipientRequest;
use Domain\PaymentMethods\Actions\AddRecipientToAccountAction;
use Domain\PaymentMethods\Actions\GetUserTransferRecipientsAction;
use Domain\PaymentMethods\Actions\UpdateRecipientAction;
use Domain\PaymentMethods\DTOs\TransferRecipientData;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\Users\Models\User;
use Illuminate\Http\JsonResponse;

class UserRecipientsController
{
    public function index(User $user, GetUserTransferRecipientsAction $getUserTransferRecipientsAction): JsonResponse
    {
        $recipients = $getUserTransferRecipientsAction($user);

        return response()->json([
            'message' => 'User transfer recipients',
            'data' => $recipients
        ]);
    }

    public function store(User $user,
                          AddRecipientToAccountRequest $request,
                          AddRecipientToAccountAction $addRecipientToAccountAction): JsonResponse
    {
        $recipientData = TransferRecipientData::fromRequest($request);
        $recipient = $addRecipientToAccountAction($user, $recipientData);

        return response()->json([
            'message' => 'Recipient successfully saved.',
            'data' => $recipient
        ], 201);
    }

    public function update(User $user, TransferRecipient $recipient, UpdateTransferRecipientRequest $request,
                           UpdateRecipientAction $updateRecipientAction): JsonResponse
    {
        $recipientData = TransferRecipientData::fromRequest($request);
        $recipient = $updateRecipientAction($recipient, $recipientData);

        return response()->json([
            'message' => 'Recipient update successful.',
            'data' => $recipient
        ]);
    }
}
