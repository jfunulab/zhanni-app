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
            'originCurrency' => $bidData['origin_currency'],
            'destinationCurrency' => $bidData['destination_currency'],
            'fundingAccount' => $bidData['funding_account_id'],
            'receivingAccount' => $bidData['receiving_account_id'],
        ]);
    }
}
