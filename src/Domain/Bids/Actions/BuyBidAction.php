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
            'amount' => $bidPurchaseData->amount,
            'minimum_amount' => $bidPurchaseData->bid->minimum_amount * 100,
            'maximum_amount' => $bidPurchaseData->bid->maximum_amount * 100,
            'rate' => $bidPurchaseData->bid->rate,
            'origin_currency' => $bidPurchaseData->bid->origin_currency,
            'destination_currency' => $bidPurchaseData->bid->destination_currency,
            'buyer_funding_account_id' => $bidPurchaseData->fundingAccount,
            'buyer_receiving_account_id' => $bidPurchaseData->receivingAccount,
        ]);
    }
}
