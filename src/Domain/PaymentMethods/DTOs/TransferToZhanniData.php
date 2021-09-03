<?php


namespace Domain\PaymentMethods\DTOs;


use Spatie\DataTransferObject\DataTransferObject;

class TransferToZhanniData extends DataTransferObject
{

    public ?int $amount;
    public ?string $description;
    public ?string $zhanniHandle;


    public static function fromArray(array $array): self
    {
        return new self([
            'amount' => $array['amount'] ?? null,
            'description' => $array['description'] ?? null,
            'zhanniHandle' => config('sila.app_handle')
        ]);
    }
}
