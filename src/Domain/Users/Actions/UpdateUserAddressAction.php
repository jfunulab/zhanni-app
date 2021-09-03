<?php


namespace Domain\Users\Actions;


use Domain\Countries\Models\Country;
use Domain\Countries\Models\CountryState;
use Domain\Users\DTOs\UserAddressData;
use Domain\Users\Models\UserAddress;

class UpdateUserAddressAction
{

    public function __invoke(UserAddress $userAddress, UserAddressData $userAddressData, $refresh = true): UserAddress
    {
        $userAddress->fill([
            'line_one' => $userAddressData->lineOne ?? $userAddress->line_one,
            'line_two' => $userAddressData->lineTwo ?? $userAddress->line_two,
            'postal_code' => $userAddressData->postalCode ?? $userAddress->postal_code,
            'country_id' => Country::where('name', $userAddressData->country)->first()->id ?? $userAddress->country_id,
            'state_id' => CountryState::where('name', $userAddressData->state)->first()->id ?? $userAddress->state_id,
        ])->save();


        return $refresh ? $userAddress->fresh(['country', 'state']) : $userAddress;
    }
}
