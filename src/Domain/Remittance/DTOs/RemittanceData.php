<?php


namespace Domain\Remittance\DTOs;


use App\ExchangeRate;
use Domain\PaymentMethods\Models\BankAccount;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\PaymentMethods\Models\UserCard;
use Spatie\DataTransferObject\DataTransferObject;

class RemittanceData extends DataTransferObject
{

    public ?float $amount;
    public ?float $price;
    public ?float $convertedAmount;
    public ?string $reason;
    public ?ExchangeRate $rate;
    public ?BankAccount $fundingAccount;
    public ?TransferRecipient $recipient;

    public static function fromArray(array $remittanceData): self
    {
        return new self([
            'amount' => (float) $remittanceData['amount'] ?? null,
            'price' => (float) $remittanceData['price'] ?? null,
            'reason' => $remittanceData['reason'] ?? null,
            'convertedAmount' => (float) $remittanceData['converted_amount'] ?? null,
            'rate' => ExchangeRate::findOrFail($remittanceData['rate']),
            'fundingAccount' => BankAccount::findOrFail($remittanceData['funding_account_id']),
            'recipient' => TransferRecipient::findOrFail($remittanceData['recipient']),
        ]);
    }
}
