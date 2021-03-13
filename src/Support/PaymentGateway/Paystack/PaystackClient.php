<?php


namespace Support\PaymentGateway\Paystack;


use Illuminate\Support\Facades\Http;

class PaystackClient
{
    private string $baseUrl = 'https://api.paystack.co';

    public function post(string $endpoint, array $data = [])
    {
        return Http::withToken(config('services.paystack.secret'))
            ->post("$this->baseUrl/$endpoint", $data);
    }

    public function get(string $endpoint, array $queryParameters = [])
    {
        return Http::withToken(config('services.paystack.secret'))
            ->get("$this->baseUrl/$endpoint", $queryParameters);
    }
}
