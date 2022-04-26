<?php

namespace App\Http\Controllers\Banks;

use Domain\PaymentMethods\Actions\GetBankListAction;
use Illuminate\Http\JsonResponse;

class BanksController
{

    public function index(GetBankListAction $getBankListAction): JsonResponse
    {
        $banks = $getBankListAction();

        return response()->json([
            'message' => 'Bank list',
            'data' => $banks
        ]);
    }
}
