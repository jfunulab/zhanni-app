<?php

namespace Tests\Feature\Bid;

use Domain\Bids\Models\Bid;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BuyBidTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_buy_a_bid()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->newUser()->create();
        Sanctum::actingAs($user);
        $bid = Bid::factory()->create();

        $response = $this->postJson("/api/bids/$bid->id/orders", [
            'bid_id' => $bid,
            'amount' => 1900
        ]);

        $response->assertStatus(201);
    }
}
