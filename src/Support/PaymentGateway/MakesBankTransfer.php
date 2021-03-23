<?php


namespace Support\PaymentGateway;


interface MakesBankTransfer
{
    public function getBankList(): void;
}
