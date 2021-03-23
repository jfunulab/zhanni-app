<?php


namespace Domain\Users\Actions;


use Domain\Users\DTOs\UserAddressData;
use Domain\Users\Models\User;
use Domain\Users\Models\UserAddress;

class AddUserAddressAction
{
    public function __invoke(User $user, UserAddressData $userAddressData): UserAddress
    {
        return $user->address()->create([
            'country_id' => $userAddressData->countryId,
            'state_id' => $userAddressData->stateId,
            'postal_code' => $userAddressData->postalCode,
            'line_one' => $userAddressData->lineOne,
            'line_two' => $userAddressData->lineTwo
        ]);
    }
}
