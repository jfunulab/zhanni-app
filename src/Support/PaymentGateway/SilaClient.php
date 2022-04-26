<?php


namespace Support\PaymentGateway;


use Silamoney\Client\Api\SilaApi;

class SilaClient
{
    public SilaApi $client;

    public function __construct()
    {
        $environment = config('sila.default');

        $this->client = SilaApi::fromEnvironment(
            config("sila.env.$environment"),
            config("sila.balance_env.$environment"),
            config('sila.app_handle'),
            config('sila.private_key')
        );
    }
}
