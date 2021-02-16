<?php


namespace Domain\Users\DTOs;


use App\Api\Users\Requests\CreateUserRequest;
use Spatie\DataTransferObject\DataTransferObject;

class UserData extends DataTransferObject
{
    public ?string $email;
    public ?string $fullName;
    public ?string $address;
    public ?string $password;

    public static function fromRequest(CreateUserRequest $request): UserData
    {
        return new self([
            'email' => $request->input('email'),
            'fullName' => $request->input('full_name'),
            'address' => $request->input('address'),
            'password' => $request->input('password'),
        ]);
    }
}
