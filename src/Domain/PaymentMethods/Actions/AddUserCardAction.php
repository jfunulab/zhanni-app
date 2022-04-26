<?php


namespace Domain\PaymentMethods\Actions;


use Domain\PaymentMethods\DTOs\UserCardData;
use Domain\Users\Models\User;
use Illuminate\Support\Str;

class AddUserCardAction
{

    public function __invoke(User $user, UserCardData $cardData)
    {
        $paymentMethod = null;
        if(!Str::contains($cardData->paymentMethodId, 'flw')){
            if(!$user->hasStripeId()){
                $user->createAsStripeCustomer();
            }

            $paymentMethod = $user->updateDefaultPaymentMethod($cardData->paymentMethodId);
        }

        return $user->cards()->create([
            'platform_id' => $cardData->paymentMethodId,
            'brand' => $paymentMethod->card->brand ?? null,
            'last_four' => $paymentMethod->card->last4 ?? null,
            'expiry_month' => $cardData->expiryMonth,
            'expiry_year' => $cardData->expiryYear,
            'postal_code' => $cardData->postalCode
        ]);
    }
}
