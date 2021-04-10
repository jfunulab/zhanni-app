<?php


namespace Support\PaymentGateway\DTOs;


use Spatie\DataTransferObject\DataTransferObject;

class BankTransferData extends DataTransferObject
{
    public ?string $accountNumber;
    public ?string $bankCode;
    public ?string $currency;
    public ?float $amount;

    public static function fromArray(array $array): BankTransferData
    {
        return new self([
            'accountNumber' => $array['account_number'] ?? null,
            'bankCode' => $array['bank_code'] ?? null,
            'amount' => $array['amount'] ?? null,
            'currency' => $array['currency'] ?? null,
        ]);
    }
}
