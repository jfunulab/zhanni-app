<?php


namespace Domain\Users\DTOs;


use Illuminate\Foundation\Http\FormRequest;
use Spatie\DataTransferObject\DataTransferObject;

class UserAddressData extends DataTransferObject
{
    public ?string $lineOne;
    public ?string $lineTwo;
    public ?int $countryId;
    public ?int $stateId;
    public ?string $postalCode;

    public static function fromRequest(FormRequest $request): self
    {
        return new self([
            'lineOne' => $request->input('line_one'),
            'lineTwo' => $request->input('line_two'),
            'countryId' => $request->input('country_id'),
            'stateId' => $request->input('state_id'),
            'postalCode' => $request->input('postal_code'),
        ]);
    }
}
