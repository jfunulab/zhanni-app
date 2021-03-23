<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Support\PaymentGateway\MakesBankTransfer;

class BankSeeder extends Seeder
{
    /**
     * @var MakesBankTransfer
     */
    private MakesBankTransfer $paymentGateway;

    /**
     * BankSeeder constructor.
     * @param MakesBankTransfer $paymentGateway
     */
    public function __construct(MakesBankTransfer $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->paymentGateway->getBankList();
    }
}
