<?php

namespace App\Http\Controllers\Banks;

use Domain\PaymentMethods\Actions\GetBankListAction;

class BanksController
{

    public function index(GetBankListAction $getBankListAction)
    {
        $banks = $getBankListAction();

        return response()->json([
            'message' => 'Bank list',
            'data' => $banks
        ]);
    }
}
