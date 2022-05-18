<?php

namespace Database\Seeders;

use Domain\PaymentMethods\Models\Bank;
use Illuminate\Database\Seeder;

class CashPickupBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bank::whereIn('slug', [
            'access-bank',
            'first-city-monument-bank',
            'globus-bank',
            'polaris-bank',
            'united-bank-for-africa',
            'wema-bank',
            'zenith-bank'
        ])->update(['cash_pickup' => true]);
    }
}
