<?php


namespace Domain\Users\Actions;


use Domain\Users\DTOs\UserData;
use Domain\Users\Models\User;

class UpdateUserAction
{

    public function __invoke(User $user, UserData $userData): User
    {
        $user->fill([
            'first_name' => $userData->firstName ?? $user->first_name,
            'last_name' => $userData->lastName ?? $user->last_name,
            'phone_number' => $userData->phoneNumber ?? $user->phone_number
        ])->save();

        return $user;
    }
}
