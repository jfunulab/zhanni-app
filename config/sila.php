<?php

use Silamoney\Client\Domain\BalanceEnvironments;
use Silamoney\Client\Domain\Environments;

return [
    'default' => env('SILA_ENVIRONMENT', 'sandbox'),

    'app_handle' => env('SILA_APP_HANDLE'),
    'private_key' => env('SILA_PRIVATE_KEY'),
    'env' => [
        'sandbox' => Environments::SANDBOX(),
        'production' => Environments::PRODUCTION(),
        'staging' => Environments::STAGE()
    ],
    'balance_env' => [
        'sandbox' => BalanceEnvironments::SANDBOX(),
        'production' => BalanceEnvironments::PRODUCTION(),
        'staging' => BalanceEnvironments::STAGE()
    ]
];
