<?php

namespace Tests\Feature;

use App\ExchangeRate;
use App\Price;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetPricesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_get_list_of_prices()
    {
        $this->withoutExceptionHandling();


        Price::factory()->create();

        $response = $this->getJson("/api/prices");

        $response->assertSuccessful();
    }
}
