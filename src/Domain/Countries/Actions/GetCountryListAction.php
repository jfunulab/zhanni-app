<?php


namespace Domain\Countries\Actions;


use Domain\Countries\Models\Country;

class GetCountryListAction
{
    public function __invoke()
    {
        return Country::all();
    }
}
