<?php


namespace Domain\Remittance\Actions;


use App\Remittance;
use Domain\Remittance\DTOs\RemittanceData;

class UpdateRemittanceAction
{

    /**
     */
    public function __invoke(Remittance $remittance, RemittanceData $data)
    {
        $updateData = [
            'fee' => $data->price,
        ];

        if($data->reason) $updateData['reason'] = $data->reason;
        if($data->amount) $updateData['base_amount'] = $data->amount;
        if($data->fundingAccount) $updateData['funding_account_id'] = $data->fundingAccount->id;
        if($data->recipient) $updateData['recipient_id'] = $data->recipient->id;

        if($data->rate){
            $updateData = array_merge($updateData, [
                'exchange_rate_id' => $data->rate->id,
                'base_currency' => $data->rate->base,
                'currency_to_remit' => $data->rate->currency,
                'amount_to_remit' => $data->amount * $data->rate->rate,
            ]);
        }

        $remittance->update($updateData);

        return $remittance->fresh(['fundingAccount', 'recipient.user']);
    }
}
