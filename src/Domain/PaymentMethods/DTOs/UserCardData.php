<?php


namespace Domain\PaymentMethods\DTOs;


use Illuminate\Foundation\Http\FormRequest;
use Spatie\DataTransferObject\DataTransferObject;

class UserCardData extends DataTransferObject
{

    public ?string $paymentMethodId;
    public ?int $expiryMonth;
    public ?int $expiryYear;
    public ?string $postalCode;

    public static function fromRequest(FormRequest $request): self
    {
        return new self([
            'paymentMethodId' => $request->input('payment_method_id'),
            'expiryMonth' => $request->input('expiry_month'),
            'expiryYear' => $request->input('expiry_year'),
            'postalCode' => $request->input('postal_code'),
        ]);
    }
}
