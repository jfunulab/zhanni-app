<?php

namespace Tests\Feature\Users;

use Domain\PaymentMethods\Models\UserCard;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetUserCardsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    function user_can_get_list_of_cards_associated_to_account()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $cards = UserCard::factory()->count(3)->for($user)->create();
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/users/$user->id/cards");

        $response->assertSuccessful()->assertJson([
            'data' => [
                [
                    'brand' => $cards[0]->brand,
                    'last_four' => $cards[0]->last_four
                ],
                [
                    'brand' => $cards[1]->brand,
                    'last_four' => $cards[1]->last_four
                ],
                [
                    'brand' => $cards[2]->brand,
                    'last_four' => $cards[2]->last_four
                ],
            ]
        ]);
    }
}
