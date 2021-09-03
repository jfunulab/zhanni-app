<?php

namespace App\Api\Users\Controllers;


use App\Http\Requests\AddUserAddressRequest;
use Domain\Users\Actions\AddUserAddressAction;
use Domain\Users\DTOs\UserAddressData;
use Domain\Users\Models\User;

class UserAddressController
{

    public function store(User $user, AddUserAddressRequest $request, AddUserAddressAction $addUserAddressAction)
    {
        $userAddressData = UserAddressData::fromArray($request->toArray());
        $address = $addUserAddressAction($user, $userAddressData);

        return response()->json([
            'message' => 'Address successfully saved.',
            'data' => $address
        ], 201);
    }
}
