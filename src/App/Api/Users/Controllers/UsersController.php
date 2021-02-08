<?php

namespace App\Api\Users\Controllers;

use App\Api\Users\Requests\CreateUserRequest;
use Domain\Users\Actions\CreateUserAction;
use Domain\Users\DTOs\UserData;
use Illuminate\Auth\Events\Registered;

class UsersController
{
    public function store(CreateUserRequest $request, CreateUserAction $createUserAction)
    {
        $userData = UserData::fromRequest($request);
        $user = $createUserAction($userData);

        event(new Registered($user));

        return response()->json([
            'message' => 'User created successfully',
            'data' => [
                'token' => $user->createToken($userData->email)->plainTextToken,
                'user' => $user
            ]
        ], 201);
    }
}
