<?php

namespace App\Http\Controllers;

use App\Exceptions\BankConnectionException;
use App\Http\Requests\ConnectPlaidBankAccountRequest;
use Domain\PaymentMethods\Actions\AddBankAccountAction;
use Domain\PaymentMethods\DTOs\UserBankAccountData;
use Domain\Users\Models\User;
use Illuminate\Http\JsonResponse;

class UserBankAccountsController extends Controller
{

    public function index(User $user)
    {
        return response()->json([
            'message' => 'User bank accounts',
            'data' => $user->bankAccounts
        ]);
    }

    public function store(User $user, AddBankAccountAction $addBankAccountAction, ConnectPlaidBankAccountRequest $request): JsonResponse
    {
        try {
            $cardsData = UserBankAccountData::fromArray(request()->all());

            $bankAccount = $addBankAccountAction($user, $cardsData);

            return response()->json([
                'message' => 'Bank account added',
                'data' => $bankAccount
            ], 201);

        } catch (BankConnectionException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->getErrors()
            ], 422);
        }
    }
}
