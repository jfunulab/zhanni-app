<?php

namespace App\Http\Controllers;

use App\Price;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricesController extends Controller
{

    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Prices',
            'data' => Price::all()
        ]);
    }
}
