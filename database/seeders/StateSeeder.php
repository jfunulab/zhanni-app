<?php

namespace Database\Seeders;

use Domain\Countries\Models\Country;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countriesStatesJsonFile = base_path('storage/app/public/states.json');
        $countryStates = collect(json_decode(file_get_contents($countriesStatesJsonFile), true));
        $countryStates->each(function($states, $countryCode){
            $country = Country::where('code', $countryCode)->first();
            if($country){
                collect($states)->each(function ($state) use($country){
                    $country->states()->firstOrCreate([
                        'name' => $state['name'],
                        'code' => $state['abbreviation']
                    ]);
                });
            }
        });
    }
}
