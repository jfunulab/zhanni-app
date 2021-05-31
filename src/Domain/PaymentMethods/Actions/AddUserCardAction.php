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
        $paymentMethod = $user->updateDefaultPaymentMethod($cardData->paymentMethodId);

        return $user->cards()->create([
            'platform_id' => $cardData->paymentMethodId,
            'brand' => $paymentMethod->card->brand,
            'last_four' => $paymentMethod->card->last4,
            'expiry_month' => $cardData->expiryMonth,
            'expiry_year' => $cardData->expiryYear,
            'postal_code' => $cardData->postalCode
        ]);
    }
}
