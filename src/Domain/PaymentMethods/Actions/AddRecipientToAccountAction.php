<?php


namespace Domain\PaymentMethods\Actions;


use Domain\PaymentMethods\DTOs\TransferRecipientData;
use Domain\Users\Models\User;

class AddRecipientToAccountAction
{
    public function __invoke(User $user, TransferRecipientData $data)
    {
        return $user->recipients()->create([
            'email' => $data->email,
            'account_name' => $data->accountName,
            'account_number' => $data->accountNumber,
            'bank_id' => $data->bankId
        ]);
    }
}
