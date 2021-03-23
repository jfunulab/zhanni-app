<?php


namespace Support\PaymentGateway\DTOs;


use Spatie\DataTransferObject\DataTransferObject;

class ResolvedBankData extends DataTransferObject
{
    public ?string $accountNumber;
    public ?string $accountName;
    public ?int $bankId;

    public static function fromArray(array $array): self
    {
        return new self([
            'accountNumber' => $array['account_number'] ?? null,
            'accountName' => $array['account_name'] ?? null,
            'bankId' => $array['bank_id'] ?? null,
        ]);
    }
}
