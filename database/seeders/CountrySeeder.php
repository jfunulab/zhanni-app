<?php

namespace Database\Seeders;

use App\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countriesJsonFile = base_path('storage/app/public/countries.json');
        $countries = collect(json_decode(file_get_contents($countriesJsonFile), true));
        $countries->each(function($country){
            Country::firstOrCreate(['name' => $country['name'], 'code' => $country['code']]);
        });
    }
}
