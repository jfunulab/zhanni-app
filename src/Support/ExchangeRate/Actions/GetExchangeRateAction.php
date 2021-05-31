<?php


namespace Support\ExchangeRate\Actions;


use App\ExchangeRate;
class GetExchangeRateAction
{
    public function __invoke(string $from, string $to)
    {
        return ExchangeRate::where(['base' => $from, 'currency' => $to])->firstOrFail();
    }
}
