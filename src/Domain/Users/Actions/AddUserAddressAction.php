<?php


namespace Domain\Users\Actions;


use Domain\Countries\Models\Country;
use Domain\Countries\Models\CountryState;
use Domain\Users\DTOs\UserData;
use Domain\Users\Models\User;

class AddUserAddressAction
{
    public function __invoke(User $user, UserData $userData)
    {
        return $user->address()->create([
            'country_id' => Country::where('name', $userData->country)->first()->id ?? null,
            'state_id' => CountryState::where('name', $userData->state)->first()->id ?? null,
            'postal_code' => $userData->postalCode,
            'line_one' => $userData->lineOne,
            'line_two' => $userData->lineTwo
        ]);
    }
}
