<?php


namespace Domain\PaymentMethods\Actions;


use Domain\PaymentMethods\DTOs\TransferRecipientData;
use Domain\Users\Models\User;

class AddRecipientToAccountAction
{
    public function __invoke(User $user, TransferRecipientData $data)
    {
        $transferRecipient = $user->recipients()->create([
            'email' => $data->email,
            'phone_number' => $data->phone_number,
            'account_name' => $data->account_name,
            'account_number' => $data->account_number,
            'bank_id' => $data->bank_id
        ]);

        return $transferRecipient->fresh(['bank']);
    }
}
