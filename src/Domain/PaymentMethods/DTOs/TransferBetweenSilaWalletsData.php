<?php


namespace Domain\PaymentMethods\DTOs;


use App\CreditPayment;
use Domain\Users\Models\User;
use Spatie\DataTransferObject\DataTransferObject;

class TransferBetweenSilaWalletsData extends DataTransferObject
{

    public int $amount;
    public User $from;
    public User $to;
    public ?string $description;


    public static function fromArray(array $array): self
    {
        return new self([
            'amount' => (int) $array['amount'] ?? 0,
            'from' => $array['from'] ?? null,
            'to' => $array['to'] ?? null,
            'description' => $array['description'] ?? null,
        ]);
    }
}
