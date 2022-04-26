<?php


namespace Domain\PaymentMethods\Actions;


use Domain\PaymentMethods\DTOs\TransferRecipientData;
use Domain\PaymentMethods\Models\TransferRecipient;

class UpdateRecipientAction
{
    public function __invoke(TransferRecipient $transferRecipient, TransferRecipientData $data)
    {
        $transferRecipient->fill(collect($data->toArray())->filter()->toArray())->save();

        return $transferRecipient->fresh(['bank']);
    }
}
