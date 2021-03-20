<?php


namespace Support\PaymentGateway;


use Support\PaymentGateway\DTOs\BankTransferData;

interface LocalPaymentGateway
{
    public function transfer(BankTransferData $transferData);
}
