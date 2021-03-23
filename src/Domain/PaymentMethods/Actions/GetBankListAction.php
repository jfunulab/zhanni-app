<?php


namespace Domain\PaymentMethods\Actions;


use Domain\PaymentMethods\Models\Bank;

class GetBankListAction
{
    public function __invoke()
    {
        return Bank::all();
    }
}
