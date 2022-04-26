<?php

namespace Database\Seeders;

use App\Price;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Price::insert([
            ['amount' => 5, 'minimum' => 0, 'maximum' => 500, 'created_at' => now(), 'updated_at' => now()],
            ['amount' => 8.5, 'minimum' => 501, 'maximum' => 1000, 'created_at' => now(), 'updated_at' => now()],
            ['amount' => 13, 'minimum' => 1001, 'maximum' => 1500, 'created_at' => now(), 'updated_at' => now()],
            ['amount' => 15, 'minimum' => 1501, 'maximum' => 2000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
