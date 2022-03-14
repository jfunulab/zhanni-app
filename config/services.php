<?php

use Silamoney\Client\Domain\Environments;

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'paystack' => [
        'key' => env('PAYSTACK_PUBLIC_KEY'),
        'secret' => env('PAYSTACK_SECRET_KEY')
    ],
    'flutterwave' => [
        'key' => env('FLUTTERWAVE_PUBLIC_KEY'),
        'secret' => env('FLUTTERWAVE_SECRET_KEY'),
        'moneywave_base_url' => env('MONEYWAVE_BASE_URL'),
        'moneywave_token' => env('MONEYWAVE_TOKEN'),
        'moneywave_lock' => env('MONEYWAVE_LOCK'),
        'moneywave_key' => env('MONEYWAVE_API_KEY'),
        'moneywave_secret' => env('MONEYWAVE_API_SECRET'),
    ],
    'stripe' => [
        'model' => \Domain\Users\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],
    'plaid' => [
        'app_name' => env('PLAID_APP_NAME'),
        'client_id' => env('PLAID_CLIENT_ID'),
        'secret' => env('PLAID_SECRET'),
        'env' => env('PLAID_ENVIRONMENT', 'sandbox')
    ],
    'slack' => [
        'dump' => env('SLACK_DUMP_CHANNEL')
    ]
];
