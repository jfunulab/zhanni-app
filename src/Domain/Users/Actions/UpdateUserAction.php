<?php


namespace Domain\Users\Actions;


use Domain\Users\DTOs\UserData;
use Domain\Users\Models\User;

class UpdateUserAction
{

    public function __invoke(User $user, UserData $userData): User
    {
        $user->fill([
            'full_name' => $userData->fullName,
            'address' => $userData->address,
            'phone_number' => $userData->phoneNumber
        ])->save();

        return $user;
    }
}
