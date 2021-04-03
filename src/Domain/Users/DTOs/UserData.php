<?php


namespace Domain\Users\DTOs;


use Illuminate\Foundation\Http\FormRequest;
use Spatie\DataTransferObject\DataTransferObject;

class UserData extends DataTransferObject
{
    public ?string $email;
    public ?string $firstName;
    public ?string $lastName;
    public ?string $password;
    public ?string $phoneNumber;
    public ?string $lineOne;
    public ?string $lineTwo;
    public ?string $country;
    public ?string $state;
    public ?string $postalCode;

    public static function fromRequest(FormRequest $request): UserData
    {
        return new self([
            'email' => $request->input('email'),
            'firstName' => $request->input('first_name'),
            'lastName' => $request->input('last_name'),
            'password' => $request->input('password'),
            'phoneNumber' => $request->input('phone_number'),
            'lineOne' => $request->input('address_line_one'),
            'lineTwo' => $request->input('address_line_two'),
            'country' => $request->input('country'),
            'state' => $request->input('state'),
            'postalCode' => $request->input('postal_code'),
        ]);
    }
}
