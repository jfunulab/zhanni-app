<?php


namespace Domain\PaymentMethods\Actions;


use Domain\Users\Models\User;

class GetUserTransferRecipientsAction
{
    public function __invoke(User $user)
    {
        return $user->recipients()->with(['bank'])->get();
    }
}
