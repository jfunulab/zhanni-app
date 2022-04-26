<?php


namespace Domain\Remittance\Actions;


use App\Exceptions\SilaException;
use App\Http\Requests\InitiateRemittanceRequest;
use Domain\PaymentMethods\Actions\IssueSilaAchDebitAction;
use Domain\PaymentMethods\DTOs\SilaDebitAchData;
use Domain\Remittance\DTOs\RemittanceData;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;

class InitiateRemittanceAction
{
    private IssueSilaAchDebitAction $issueSilaAchDebitAction;

    public function __construct(IssueSilaAchDebitAction $issueSilaAchDebitAction)
    {
        $this->issueSilaAchDebitAction = $issueSilaAchDebitAction;
    }

    /**
     * @throws SilaException
     */
    public function __invoke(User $user, InitiateRemittanceRequest $initiateRemittanceRequest): ?Model
    {
        $remittanceData = RemittanceData::fromArray($initiateRemittanceRequest->toArray());
        $data = SilaDebitAchData::fromArray([
            'amount' => $remittanceData->amount,
            'price' => $remittanceData->price,
            'description' => $remittanceData->reason
        ]);
        $silaDebitResponse = ($this->issueSilaAchDebitAction)($remittanceData->fundingAccount, $data);

        if ($silaDebitResponse->getStatusCode() != 200) {
            throw new SilaException('Sila debit ACH failed.');
        }

        $remittance = $user->remittances()->create([
            'type' => $remittanceData->type,
            'base_amount' => $remittanceData->amount,
            'exchange_rate_id' => $remittanceData->rate->id,
            'reason' => $remittanceData->reason,
            'base_currency' => $remittanceData->rate->base,
            'amount_to_remit' => $remittanceData->amount * $remittanceData->rate->rate,
            'fee' => $remittanceData->price,
            'currency_to_remit' => $remittanceData->rate->currency,
            'funding_account_id' => $remittanceData->fundingAccount->id,
            'recipient_id' => $remittanceData->recipient->id,
            'pickup_bank_id' => $remittanceData->pickupBank->id ?? null
        ]);

        $remittanceData->fundingAccount->creditPayments()->create([
            'remittance_id' => $remittance->id,
            'reference_id' => $silaDebitResponse->getData()->getTransactionId(),
            'amount' => $remittanceData->totalAmount,
            'amount_in_cents' => $remittanceData->totalAmount * 100,
            'currency' => $remittanceData->rate->base,
            'status' => 'queued'
        ]);

        return $remittance->fresh(['creditPayment.sourceable', 'fundingAccount', 'debitPayment.recipient.bank', 'recipient.bank', 'recipient.user']);
    }
}
