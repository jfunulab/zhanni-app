<?php

namespace Tests\Feature\Banks;

use Domain\PaymentMethods\Models\Bank;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetCashPickupBankListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_get_list_of_available_banks()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(User::factory()->create());

        Bank::factory()->count(5)->create();
        Bank::factory()->count(3)->cash()->create();

        $response = $this->getJson("/api/cash-pickup-banks");

        $response->assertSuccessful();

        tap($response->decodeResponseJson(), function($response){
            $this->assertCount(3, $response['data']);
        });
    }
}
