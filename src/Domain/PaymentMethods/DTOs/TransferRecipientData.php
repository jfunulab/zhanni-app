<?php


namespace Domain\PaymentMethods\DTOs;


use Illuminate\Foundation\Http\FormRequest;
use Spatie\DataTransferObject\DataTransferObject;

class TransferRecipientData extends DataTransferObject
{

    public ?int $bankId;
    public ?string $email;
    public ?string $accountName;
    public ?string $accountNumber;

    public static function fromRequest(FormRequest $request): self
    {
        return new self([
            'bankId' => $request->input('bank_id'),
            'email' => $request->input('email'),
            'accountName' => $request->input('account_name'),
            'accountNumber' => $request->input('account_number'),
        ]);
    }
}
