<?php


namespace Domain\Remittance\DTOs;


use App\ExchangeRate;
use App\Price;
use Domain\PaymentMethods\Models\BankAccount;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\PaymentMethods\Models\UserCard;
use Spatie\DataTransferObject\DataTransferObject;

class RemittanceData extends DataTransferObject
{

    public ?float $amount;
    public ?float $price;
    public ?float $totalAmount;
    public ?float $convertedAmount;
    public ?string $reason;
    public ?ExchangeRate $rate;
    public ?BankAccount $fundingAccount;
    public ?TransferRecipient $recipient;

    public static function fromArray(array $remittanceData): self
    {
        $parameters = [
            'amount' => (float)$remittanceData['amount'] ?? null,
            'reason' => $remittanceData['reason'] ?? null,
            'convertedAmount' =>  null,
            'rate' => ExchangeRate::findOrFail($remittanceData['rate']),
            'fundingAccount' => BankAccount::findOrFail($remittanceData['funding_account_id']),
            'recipient' => TransferRecipient::findOrFail($remittanceData['recipient']),
        ];

        $price = Price::get()->first(function($price) use($remittanceData){
            return $remittanceData['amount'] >= $price->minimum &&  $remittanceData['amount'] <= $price->maximum;
        });

        $parameters['price'] = $price->amount ?? 0.0;
        $parameters['totalAmount'] = $parameters['amount'] + $parameters['price'];

        return new self($parameters);
    }
}
