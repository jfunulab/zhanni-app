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

    public static function fromArray(array $dataArray): UserData
    {
        return new self([
            'email' => $dataArray['email'] ?? null,
            'firstName' => $dataArray['first_name'] ?? null,
            'lastName' => $dataArray['last_name'] ?? null,
            'password' => $dataArray['password'] ?? null,
            'phoneNumber' => $dataArray['phone_number'] ?? null,
            'lineOne' => $dataArray['address_line_one'] ?? null,
            'lineTwo' => $dataArray['address_line_two'] ?? null,
            'country' => $dataArray['country'] ?? null,
            'state' => $dataArray['state'] ?? null,
            'postalCode' => $dataArray['postal_code'] ?? null,
        ]);
    }
}
