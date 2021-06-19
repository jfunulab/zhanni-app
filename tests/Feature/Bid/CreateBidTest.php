<?php

namespace Tests\Feature\Bid;

use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\PaymentMethods\Models\UserCard;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateBidTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_create_bids_for_sale()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->newUser()->create();
        Sanctum::actingAs($user);
        $fundingSource = UserCard::factory()->create(['user_id' => $user->id]);
        $receivingAccount = TransferRecipient::factory()->create(['user_id' => $user->id]);

        $bidDetails = [
            'rate' => 450,
            'origin_currency' => 'USD',
            'destination_currency' => 'NGN',
            'minimum_amount' => 1500,
            'maximum_amount' => 2500,
            'funding_account_id' => $fundingSource->id,
            'receiving_account_id' => $receivingAccount->id
        ];

        $response = $this->postJson("/api/users/$user->id/bids", $bidDetails);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Bid successfully created.',
                'data' => [
                    'rate' => $bidDetails['rate']
                ]
            ]);

        $this->assertCount(1, $user->bids);
    }
}
