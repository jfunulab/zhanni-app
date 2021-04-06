<?php


namespace Domain\PaymentMethods\Actions;


use Domain\PaymentMethods\DTOs\UserCardData;
use Domain\PaymentMethods\Models\UserCard;
use Domain\Users\Models\User;

class AddUserCardAction
{

    public function __invoke(User $user, UserCardData $cardData)
    {
        if(!$user->hasStripeId()){
            $user->createAsStripeCustomer();
        }
        $user->updateDefaultPaymentMethod($cardData->paymentMethodId);

        return $user->cards()->create([
            'platform_id' => $cardData->paymentMethodId,
            'expiry_month' => $cardData->expiryMonth,
            'expiry_year' => $cardData->expiryYear,
            'postal_code' => $cardData->postalCode
        ]);
    }
}
