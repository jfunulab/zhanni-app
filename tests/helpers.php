<?php

use Stripe\PaymentMethod;
use Stripe\Stripe;


function getStripeToken(): array
{
    Stripe::setApiKey(config('services.stripe.key'));
    $paymentMethod = PaymentMethod::create([
        'type' => 'card',
        'card' => [
            'number' => '4242424242424242',
            'exp_month' => 2,
            'exp_year' => 2022,
            'cvc' => '314',
        ],
    ]);

    return $paymentMethod->toArray();
}
