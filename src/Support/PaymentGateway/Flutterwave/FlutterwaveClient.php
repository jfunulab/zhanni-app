<?php


namespace Support\PaymentGateway\Flutterwave;


use Illuminate\Support\Facades\Http;

class FlutterwaveClient
{
//    private string $baseUrl = 'https://staging.moneywaveapp.com/v1';
    private string $baseUrl = 'https://live.moneywaveapi.co/v1';

    public function post(string $endpoint, array $data = [])
    {
        return Http::withHeaders(['Authorization' => config('services.flutterwave.moneywave_token')])
            ->post("$this->baseUrl/$endpoint", $data)->json();

//        return Http::log()->withToken(config('services.flutterwave.moneywave_token'))
//            ->post("$this->baseUrl/$endpoint", $data)->json();
    }

    public function get(string $endpoint, array $queryParameters = [])
    {
        return Http::withToken(config('services.flutterwave.secret'))
            ->get("$this->baseUrl/$endpoint", $queryParameters);
    }
}
