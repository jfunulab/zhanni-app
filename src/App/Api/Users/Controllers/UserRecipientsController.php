<?php

namespace App\Api\Users\Controllers;

use App\Http\Requests\AddRecipientToAccountRequest;
use Domain\PaymentMethods\Actions\AddRecipientToAccountAction;
use Domain\PaymentMethods\DTOs\TransferRecipientData;
use Domain\Users\Models\User;
use Illuminate\Http\JsonResponse;

class UserRecipientsController
{
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
}
