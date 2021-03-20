<?php

namespace App\Api\Users\Controllers;

use App\Http\Requests\AddCardRequest;
use Domain\PaymentMethods\Actions\AddUserCardAction;
use Domain\PaymentMethods\DTOs\UserCardData;
use Domain\Users\Models\User;

class UserCardsController
{

    public function store(User $user, AddCardRequest $request, AddUserCardAction $addUserCardAction)
    {
        $cardsData = UserCardData::fromRequest($request);
        $card = $addUserCardAction($user, $cardsData);

        return response()->json([
            'message' => 'Card successfully added.',
            'data' => $card
        ], 201);
    }
}
