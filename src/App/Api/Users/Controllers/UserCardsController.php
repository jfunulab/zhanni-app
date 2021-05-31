<?php

namespace App\Api\Users\Controllers;

use App\Api\Users\Requests\GetUserCardsRequest;
use App\Http\Requests\AddCardRequest;
use Domain\PaymentMethods\Actions\AddUserCardAction;
use Domain\PaymentMethods\Actions\GetUserCardsAction;
use Domain\PaymentMethods\DTOs\UserCardData;
use Domain\Users\Models\User;
use Illuminate\Http\JsonResponse;

class UserCardsController
{

    public function index(User $user, GetUserCardsRequest $request, GetUserCardsAction $getUserCardsAction): JsonResponse
    {
        $cards = $getUserCardsAction($user);

        return response()->json([
            'message' => 'User cards.',
            'data' => $cards
        ]);
    }

    public function store(User $user, AddCardRequest $request, AddUserCardAction $addUserCardAction):JsonResponse
    {
        $cardsData = UserCardData::fromRequest($request);
        $card = $addUserCardAction($user, $cardsData);

        return response()->json([
            'message' => 'Card successfully added.',
            'data' => $card
        ], 201);
    }
}
