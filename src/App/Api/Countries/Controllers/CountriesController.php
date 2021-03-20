<?php

namespace App\Api\Countries\Controllers;

use Domain\Countries\Actions\GetCountryListAction;

class CountriesController
{

    public function index(GetCountryListAction $getCountryListAction)
    {
        $countries = $getCountryListAction();

        return response()->json([
            'message' => 'Countries',
            'data' => $countries
        ]);
    }
}
