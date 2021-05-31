<?php


namespace Domain\PaymentMethods\Actions;


use Domain\Users\Models\User;

class GetUserCardsAction
{

    public function __invoke(User $user)
    {
        return $user->cards;
    }
}
