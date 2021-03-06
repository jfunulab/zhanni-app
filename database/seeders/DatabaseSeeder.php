<?php

namespace Database\Seeders;

use App\ExchangeRate;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountrySeeder::class,
            StateSeeder::class,
            BankSeeder::class,
            CashPickupBankSeeder::class,
            ExchangeRateSeeder::class,
            PriceSeeder::class,
            VerificationCategorySeeder::class,
            VerificationDocumentSeeder::class,
            VerificationCategoryDocumentSeeder::class
        ]);
    }
}
