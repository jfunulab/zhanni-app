<?php

namespace Tests\Feature\Bid;

use Domain\Bids\Models\Bid;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ViewAllAvailableBidsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_view_all_available_bids()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->newUser()->create();
        Sanctum::actingAs($user);

        Bid::factory()->count(50)->create();

        $response = $this->getJson("/api/bids");

        $response->assertSuccessful();
    }
}
