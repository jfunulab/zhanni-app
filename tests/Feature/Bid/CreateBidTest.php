<?php

namespace Tests\Feature\Bid;

use Domain\PaymentMethods\Models\BankAccount;
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
        $receivingAccount = BankAccount::factory()->create(['user_id' => $user->id]);

        $bidDetails = [
            'rate' => 450,
            'origin_currency' => 'USD',
            'destination_currency' => 'NGN',
            'minimum_amount' => 1500,
            'maximum_amount' => 2500,
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

    /** @test */
    function receiving_bank_account_is_required_when_creating_a_bid()
    {
        $user = User::factory()->newUser()->create();
        Sanctum::actingAs($user);

        $bidDetails = [
            'rate' => 450,
            'origin_currency' => 'USD',
            'destination_currency' => 'NGN',
            'minimum_amount' => 1500,
            'maximum_amount' => 2500,
        ];

        $response = $this->postJson("/api/users/$user->id/bids", $bidDetails);

        $response->assertStatus(422);
    }

    /** @test */
    function receiving_bank_account_must_be_a_valid_bank_account_id_existing()
    {
        $user = User::factory()->newUser()->create();
        Sanctum::actingAs($user);

        $bidDetails = [
            'rate' => 450,
            'origin_currency' => 'USD',
            'destination_currency' => 'NGN',
            'minimum_amount' => 1500,
            'maximum_amount' => 2500,
            'receiving_account_id' => 1
        ];

        $response = $this->postJson("/api/users/$user->id/bids", $bidDetails);

        $response->assertStatus(422);
    }
}
