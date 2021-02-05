<?php

namespace App\Api\Users\Controllers;

use App\Api\Users\Requests\CreateUserRequest;
use Domain\Users\Actions\CreateUserAction;
use Domain\Users\DTOs\UserData;

class UsersController
{
    public function store(CreateUserRequest $request, CreateUserAction $createUserAction)
    {
        $userData = UserData::fromRequest($request);
        $user = $createUserAction($userData);

        return response()->json([], 201);
    }
}
