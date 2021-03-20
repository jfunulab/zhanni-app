<?php

namespace App\Api\Countries\Controllers;

use Domain\Countries\Actions\GetCountryStateListAction;
use Domain\Countries\Models\Country;
use Illuminate\Http\JsonResponse;

class CountryStatesController
{

    public function index(Country $country, GetCountryStateListAction $getCountryListAction): JsonResponse
    {
        $states = $getCountryListAction($country);

        return response()->json([
           'message'=> 'Country states',
           'data' => $states
        ]);
    }
}
