<?php


namespace Domain\Users\DTOs;


use Spatie\DataTransferObject\DataTransferObject;

class UserData extends DataTransferObject
{
    public ?string $email;
    public ?string $firstName;
    public ?string $lastName;
    public ?string $password;
    public ?string $username;
    public ?string $phoneNumber;
    public ?string $birthDate;
    public ?string $identityNumber;
    public ?UserAddressData $addressData;

    public static function fromArray(array $dataArray): UserData
    {
        return new self([
            'email' => $dataArray['email'] ?? null,
            'firstName' => $dataArray['first_name'] ?? null,
            'lastName' => $dataArray['last_name'] ?? null,
            'username' => $dataArray['username'] ?? null,
            'password' => $dataArray['password'] ?? null,
            'phoneNumber' => $dataArray['phone_number'] ?? null,
            'identityNumber' => $dataArray['identity_number'] ?? null,
            'birthDate' => $dataArray['birth_date'] ?? null,
            'addressData' => UserAddressData::fromArray([
                'address_line_one' => $dataArray['address_line_one'] ?? null,
                'address_line_two' => $dataArray['address_line_two'] ?? null,
                'country' => $dataArray['country'] ?? null,
                'state' => $dataArray['state'] ?? null,
                'postal_code' => $dataArray['postal_code'] ?? null,
                'city' => $dataArray['city'] ?? null,
            ])
        ]);
    }
}
