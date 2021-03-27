<?php


namespace Support\ExchangeRate\Actions;


use Illuminate\Support\Facades\Http;

class GetExchangeRateAction
{
    public function __invoke(string $from, $to)
    {
        return 380;
    }
}
