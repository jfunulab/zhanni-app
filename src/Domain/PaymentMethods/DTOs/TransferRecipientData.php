<?php


namespace Domain\PaymentMethods\DTOs;


use Illuminate\Foundation\Http\FormRequest;
use Spatie\DataTransferObject\DataTransferObject;

class TransferRecipientData extends DataTransferObject
{

    public ?int $bank_id;
    public ?string $email;
    public ?string $phone_number;
    public ?string $account_name;
    public ?string $account_number;

    public static function fromRequest(FormRequest $request): self
    {
        return new self([
            'bank_id' => $request->input('bank_id'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'account_name' => $request->input('account_name'),
            'account_number' => $request->input('account_number'),
        ]);
    }
}
