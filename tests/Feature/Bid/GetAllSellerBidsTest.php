<?php

namespace Tests\Feature\Bid;

use Domain\Bids\Models\Bid;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetAllSellerBidsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_a_list_of_all_bids_he_created()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->has(Bid::factory()->count(4))->create();
        Sanctum::actingAs($user);

        $response = $this->getJson("api/users/$user->id/bids");

        $response->assertSuccessful();

        tap($response->decodeResponseJson(), function ($response){
            $this->assertCount(4, $response['data']['data']);
        });
    }
}
