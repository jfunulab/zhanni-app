<?php


namespace Domain\Users\DTOs;


use Illuminate\Foundation\Http\FormRequest;
use Spatie\DataTransferObject\DataTransferObject;

class UserData extends DataTransferObject
{
    public ?string $email;
    public ?string $fullName;
    public ?string $address;
    public ?string $password;
    public ?string $phoneNumber;

    public static function fromRequest(FormRequest $request): UserData
    {
        return new self([
            'email' => $request->input('email'),
            'fullName' => $request->input('full_name'),
            'address' => $request->input('address'),
            'password' => $request->input('password'),
            'phoneNumber' => $request->input('phone_number'),
        ]);
    }
}
