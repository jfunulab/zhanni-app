<?php

namespace Tests\Feature\Bid;

use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        $bidDetails = [
            'rate' => 450,
            'origin_currency' => 'USD',
            'destination_currency' => 'NGN',
            'minimum_amount' => 1500,
            'maximum_amount' => 2500,
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
