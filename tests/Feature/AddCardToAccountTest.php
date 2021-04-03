<?php

namespace Tests\Feature;

use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AddCardToAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_add_a_stripe_card_to_his_account()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->newUser()->create();
        Sanctum::actingAs($user);

        $cardDetails = [
            'payment_method_id' => getStripeToken()['id'],
            'expiry_month' => 05,
            'expiry_year' => 2022,
            'postal_code' => 004455
        ];

        $response = $this->postJson("/api/users/$user->id/cards", $cardDetails);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Card successfully added.',
                'data' => [
                    'postal_code' => $cardDetails['postal_code']
                ]
            ]);

        $this->assertCount(1, $user->cards);
    }
}