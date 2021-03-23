<?php

namespace Tests\Feature\Banks;

use Domain\PaymentMethods\Models\Bank;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetBankListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_get_list_of_available_banks()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(User::factory()->create());

        Bank::factory()->count(5)->create();

        $response = $this->getJson("/api/banks");

        $response->assertSuccessful();

        tap($response->decodeResponseJson(), function($response){
            $this->assertCount(5, $response['data']);
        });
    }
}
