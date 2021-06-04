<?php


namespace Domain\Bids\Actions;


use Domain\Bids\DTOs\BidPurchaseData;
use Domain\Bids\Models\Bid;

class BuyBidAction
{
    public function __invoke($user, Bid $bid, BidPurchaseData $bidPurchaseData)
    {
        return $user->bidBuyOrders()->create([
            'bid_id' => $bid->id,
            'seller_id' => $bid->user->id,
            'minimum_amount' => $bidPurchaseData->bid->minimum_amount,
            'maximum_amount' => $bidPurchaseData->bid->maximum_amount,
            'rate' => $bidPurchaseData->bid->rate,
            'origin_currency' => $bidPurchaseData->bid->origin_currency,
            'destination_currency' => $bidPurchaseData->bid->destination_currency
        ]);
    }
}
