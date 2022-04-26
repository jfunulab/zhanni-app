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
        ExchangeRate::firstOrNew([
            'base' => 'USD',
            'currency' => 'NGN',
        ])->fill(['rate' => 380.88])->save();

        ExchangeRate::firstOrNew([
            'base' => 'USD',
            'currency' => 'USD',
        ])->fill(['rate' => 1])->save();
    }
}
