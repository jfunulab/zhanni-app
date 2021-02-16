<?php


namespace Domain\Users\Actions;


use Domain\Users\DTOs\UserData;
use Domain\Users\Models\User;

class CreateUserAction
{
    public function __invoke(UserData $userData): User
    {
        return User::create([
            'email' => $userData->email,
            'full_name' => $userData->fullName,
            'address' => $userData->address,
            'password' => bcrypt($userData->password)
        ]);
    }
}
