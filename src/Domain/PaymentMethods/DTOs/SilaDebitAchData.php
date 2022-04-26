<?php


namespace Domain\PaymentMethods\DTOs;


use Spatie\DataTransferObject\DataTransferObject;

class SilaDebitAchData extends DataTransferObject
{

    public ?int $amount;
    public ?float $price;
    public ?string $description;
    public ?string $accountName;


    public static function fromArray(array $array): self
    {
        return new self([
            'amount' => (int) $array['amount'] ?? null,
            'price' => $array['price'] ?? null,
            'description' => $array['description'] ?? null,
            'accountName' => $array['account_name'] ?? 'default'
        ]);
    }
}
