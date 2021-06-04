<?php

namespace Tests\Feature\Bid;

use Domain\Bids\Models\BidOrder;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetSellerBidOffers extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_get_list_of_orders_that_have_been_place_on_bids()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->newUser()->create();
        Sanctum::actingAs($user);

        BidOrder::factory()->count(20)->create(['seller_id' => $user->id]);

        $response = $this->getJson("/api/users/$user->id/bids/sell-orders");

        $response->assertSuccessful();
    }
}
