<?php


namespace Domain\Users\DTOs;


use App\Api\Users\Requests\CreateUserRequest;
use Spatie\DataTransferObject\DataTransferObject;

class UserData extends DataTransferObject
{
    public ?string $email;

    public static function fromRequest(CreateUserRequest $request): UserData
    {
        return new self([
            'email' => $request->input('email')
        ]);
    }
}
