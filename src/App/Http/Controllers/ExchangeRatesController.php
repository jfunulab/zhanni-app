<?php

namespace App\Http\Controllers;


use App\Http\Requests\GetExchangeRatesRequest;
use Illuminate\Http\JsonResponse;
use Support\ExchangeRate\Actions\GetExchangeRateAction;

class ExchangeRatesController extends Controller
{

    public function index(GetExchangeRatesRequest $request, GetExchangeRateAction $getExchangeRateAction): JsonResponse
    {
        $rate = $getExchangeRateAction($request->from, $request->to);

        return response()->json([
            'message' => 'Exchange rate.',
            'data' => $rate
        ]);
    }
}
