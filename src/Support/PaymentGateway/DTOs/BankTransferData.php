<?php


namespace Support\PaymentGateway\DTOs;


use App\DebitPayment;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\Users\Models\User;
use Spatie\DataTransferObject\DataTransferObject;

class BankTransferData extends DataTransferObject
{
    public ?string $creditReference;
    public ?DebitPayment $debitPayment;
    public ?TransferRecipient $recipient;
    public ?User $sender;
    public ?string $description;

    public static function fromArray(User $sender, array $array): BankTransferData
    {
        return new self([
            'sender' => $sender,
            'debitPayment' => $array['debit_payment'] ?? null,
            'recipient' => $array['recipient'] ?? null,
            'description' => $array['description'] ?? null,
        ]);
    }
}
