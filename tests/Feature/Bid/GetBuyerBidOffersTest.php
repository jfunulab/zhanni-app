<?php

namespace Tests\Feature\Bid;

use Domain\Bids\Models\BidOrder;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetBuyerBidOffersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_view_list_of_bids_he_bought()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->newUser()->create();
        Sanctum::actingAs($user);

        BidOrder::factory()->count(20)->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/users/$user->id/bids/buy-orders");

        $response->assertSuccessful();
    }
}
