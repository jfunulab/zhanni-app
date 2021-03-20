<?php


namespace Domain\Countries\Actions;



use Domain\Countries\Models\Country;

class GetCountryStateListAction
{
    public function __invoke(Country $country)
    {
        return $country->states;
    }
}
