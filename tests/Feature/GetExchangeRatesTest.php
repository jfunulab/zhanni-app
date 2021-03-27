<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetExchangeRatesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_get_exchange_rate_for_two_currencies()
    {
        $this->withoutExceptionHandling();

        $response = $this->getJson("/api/exchange-rates?from=USD&to=NG");

        $response->assertSuccessful();
    }
}
