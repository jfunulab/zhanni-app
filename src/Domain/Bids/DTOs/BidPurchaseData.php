<?php


namespace Domain\Bids\DTOs;


use Domain\Bids\Models\Bid;
use Spatie\DataTransferObject\DataTransferObject;

class BidPurchaseData extends DataTransferObject
{

    public Bid $bid;
    public int $amount;
    public int $rate;
    public string $originCurrency;
    public string $destinationCurrency;

    public static function fromArray(Bid $bid, array $bidPurchaseData): self
    {
        return new self([
            'bid' => $bid,
            'amount' => ($bidPurchaseData['amount'] * 100) ?? null,
            'rate' => (int) $bid->rate,
            'originCurrency' => $bid->origin_currency,
            'destinationCurrency' => $bid->destination_currency,
        ]);
    }
}
