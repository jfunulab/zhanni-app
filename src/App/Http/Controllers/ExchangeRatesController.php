<?php

namespace App\Http\Controllers;


use App\Http\Requests\GetExchangeRatesRequest;
use Support\ExchangeRate\Actions\GetExchangeRateAction;

class ExchangeRatesController extends Controller
{

    public function index(GetExchangeRatesRequest $request, GetExchangeRateAction $getExchangeRateAction)
    {
        $rate = $getExchangeRateAction($request->from, $request->to);

        return response()->json([
            'message' => '',
            'data' => $rate
        ]);
    }
}
