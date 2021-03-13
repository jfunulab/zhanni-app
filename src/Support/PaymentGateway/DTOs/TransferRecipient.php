<?php


namespace Support\PaymentGateway\DTOs;


use Spatie\DataTransferObject\DataTransferObject;

class TransferRecipient extends DataTransferObject
{
    public ?string $recipientCode;

    public static function fromArray(array $array): self
    {
        return new self([
            'recipientCode' => $array['recipient_code'] ?? null,
        ]);
    }
}
