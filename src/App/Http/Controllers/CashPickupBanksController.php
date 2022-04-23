<?php

namespace App\Http\Controllers;

use Domain\PaymentMethods\Models\Bank;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CashPickupBanksController extends Controller
{

    public function index(): JsonResponse
    {
        $banks = Bank::allowsCashPickup()->get();

        return response()->json([
            'message' => 'Bank list',
            'data' => $banks
        ]);
    }
}
