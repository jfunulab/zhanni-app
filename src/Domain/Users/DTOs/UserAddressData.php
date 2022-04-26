<?php


namespace Domain\Users\DTOs;


use Spatie\DataTransferObject\DataTransferObject;

class UserAddressData extends DataTransferObject
{
    public ?string $lineOne;
    public ?string $lineTwo;
    public ?string $country;
    public ?string $state;
    public ?string $city;
    public ?string $postalCode;

    public static function fromArray(array $request): self
    {
        return new self([
            'lineOne' => $request['address_line_one'] ?? null,
            'lineTwo' => $request['address_line_two'] ?? null,
            'country' => $request['country'] ?? null,
            'state' => $request['state'] ?? null,
            'city' => $request['city'] ?? null,
            'postalCode' => $request['postal_code'] ?? null,
        ]);
    }
}
