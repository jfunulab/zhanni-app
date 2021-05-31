<?php

namespace Tests\Feature;

use App\ExchangeRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetExchangeRatesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_get_exchange_rate_for_two_currencies()
    {
        $this->withoutExceptionHandling();
        $rateDetails = [
            'rate' => 381.00,
            'currency' => 'NGN'
        ];
        ExchangeRate::factory()->create($rateDetails);

        $response = $this->getJson("/api/exchange-rates?from=USD&to=NGN");

        $response->assertSuccessful()->assertJson([
            'data' => [
                'rate' => $rateDetails['rate']
            ]
        ]);
    }
}
