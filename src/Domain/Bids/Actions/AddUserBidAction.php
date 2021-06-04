<?php


namespace Domain\Bids\Actions;


use Domain\Bids\DTOs\BidData;
use Domain\Countries\Models\Country;
use Domain\Countries\Models\CountryState;
use Domain\Users\Models\User;

class AddUserBidAction
{
    public function __invoke(User $user, BidData $bidData)
    {
        return $user->bids()->create([
            'minimum_amount' => $bidData->minimumAmount,
            'maximum_amount' => $bidData->maximumAmount,
            'rate' => $bidData->rate,
            'origin_currency' => $bidData->originCurrency,
            'destination_currency' => $bidData->destinationCurrency
        ]);
    }
}
