<?php


namespace Domain\Users\Actions;


use Domain\Countries\Models\Country;
use Domain\Countries\Models\CountryState;
use Domain\Users\DTOs\UserData;
use Domain\Users\Models\UserAddress;

class UpdateUserAddressAction
{

    public function __invoke(UserAddress $userAddress, UserData $userData): UserAddress
    {
        $userAddress->fill([
            'line_one' => $userData->lineOne ?? $userAddress->line_one,
            'line_two' => $userData->lineTwo ?? $userAddress->line_two,
            'postal_code' => $userData->postalCode ?? $userAddress->postal_code,
            'country_id' => Country::where('name', $userData->country)->first()->id ?? $userAddress->country_id,
            'state_id' => CountryState::where('name', $userData->state)->first()->id ?? $userAddress->state_id,
        ])->save();


        return $userAddress->fresh(['country', 'state']);
    }
}
