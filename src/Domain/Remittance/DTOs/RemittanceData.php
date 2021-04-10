<?php


namespace Domain\Remittance\DTOs;


use App\ExchangeRate;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\PaymentMethods\Models\UserCard;
use Spatie\DataTransferObject\DataTransferObject;

class RemittanceData extends DataTransferObject
{

    public ?float $amount;
    public ?float $convertedAmount;
    public ?ExchangeRate $rate;
    public ?UserCard $card;
    public ?TransferRecipient $recipient;

    public static function fromArray(array $remittanceData): self
    {
        return new self([
            'amount' => (float) $remittanceData['amount'] ?? null,
            'convertedAmount' => (float) $remittanceData['converted_amount'] ?? null,
            'rate' => ExchangeRate::findOrFail($remittanceData['rate']),
            'card' => UserCard::findOrFail($remittanceData['card']),
            'recipient' => TransferRecipient::findOrFail($remittanceData['recipient']),
        ]);
    }
}
