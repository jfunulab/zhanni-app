<?php

namespace Tests\Feature\Bid;

use Domain\Bids\Models\Bid;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\PaymentMethods\Models\UserCard;
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

        $fundingSource = UserCard::factory()->create(['user_id' => $user->id]);
        $receivingAccount = TransferRecipient::factory()->create(['user_id' => $user->id]);

        $response = $this->postJson("/api/bids/$bid->id/orders", [
            'bid_id' => $bid->id,
            'amount' => 1900,
            'buyer_funding_account_id' => $fundingSource->id,
            'buyer_receiving_account_id' => $receivingAccount->id,
        ]);

        $response->assertStatus(201);
    }
}
