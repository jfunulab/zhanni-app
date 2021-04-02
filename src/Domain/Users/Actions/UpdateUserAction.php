<?php


namespace Domain\Users\Actions;


use Domain\Users\DTOs\UserData;
use Domain\Users\Models\User;

class UpdateUserAction
{

    public function __invoke(User $user, UserData $userData): User
    {
        $user->fill([
            'first_name' => $userData->firstName,
            'last_name' => $userData->lastName,
            'phone_number' => $userData->phoneNumber
        ])->save();

        return $user;
    }
}
