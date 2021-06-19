<?php


namespace Domain\Bids\DTOs;


use Spatie\DataTransferObject\DataTransferObject;

class BidData extends DataTransferObject
{

    public ?int $minimumAmount;
    public ?int $maximumAmount;
    public ?int $rate;
    public ?int $fundingAccount;
    public ?int $receivingAccount;
    public ?string $originCurrency;
    public ?string $destinationCurrency;

    public static function fromArray(array $bidData): self
    {
        return new self([
            'minimumAmount' => ($bidData['minimum_amount'] * 100) ?? null,
            'maximumAmount' => ($bidData['maximum_amount'] * 100) ?? null,
            'rate' => $bidData['rate'] ?? null,
            'originCurrency' => $bidData['origin_currency'] ?? null,
            'destinationCurrency' => $bidData['destination_currency'] ?? null,
            'fundingAccount' => $bidData['funding_account_id'] ?? null,
            'receivingAccount' => $bidData['receiving_account_id'] ?? null,
        ]);
    }
}
