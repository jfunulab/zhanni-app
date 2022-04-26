<?php


namespace Support\PaymentGateway\Flutterwave;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FlutterwaveClient
{
//    private string $baseUrl = 'https://live.moneywaveapi.co/v1';


    public function post(string $endpoint, array $data = [])
    {
        $baseUrl = config('services.flutterwave.moneywave_base_url');

        return Http::withHeaders(['Authorization' => Cache::remember('moneywaveToken', 3300, function () {
                return $this->getToken();
            })
        ])->post("$baseUrl/$endpoint", $data)->json();
    }

    private function getToken()
    {
        $response = Http::post('https://staging.moneywaveapp.com/v1/merchant/verify', [
            'apiKey' => config('services.flutterwave.moneywave_key'),
            'secret' => config('services.flutterwave.moneywave_secret'),
        ])->json();

        return $response['token'];
    }
}
