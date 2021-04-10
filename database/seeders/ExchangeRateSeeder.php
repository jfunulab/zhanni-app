<?php

namespace Database\Seeders;

use App\ExchangeRate;
use Illuminate\Database\Seeder;

class ExchangeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExchangeRate::firstOrCreate([
            'base' => 'USD',
            'currency' => 'NGN',
            'rate' => 380.88
        ]);
    }
}
