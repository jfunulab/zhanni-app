<?php

namespace App\Api\Users\Controllers;

use App\Api\Users\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Domain\Users\Actions\CreateUserAction;
use Domain\Users\Actions\UpdateUserAction;
use Domain\Users\DTOs\UserData;
use Domain\Users\Models\User;
use Illuminate\Auth\Events\Registered;

class UsersController
{
    public function store(CreateUserRequest $request, CreateUserAction $createUserAction)
    {
        $userData = UserData::fromArray($request->all());
        $user = $createUserAction($userData);

        event(new Registered($user));

        return response()->json([
            'message' => 'User created successfully',
            'data' => [
                'token' => $user->createToken($userData->email, ['*'])->plainTextToken,
                'user' => $user
            ]
        ], 201);
    }

    public function update(User $user, UpdateUserRequest $request, UpdateUserAction $updateUserAction)
    {
        $userData = UserData::fromArray($request->all());

        $user = $updateUserAction($user, $userData);

        return response()->json([
            'message' => 'User update successful.',
            'data' => [
                'user' => $user
            ]
        ]);
    }
}
