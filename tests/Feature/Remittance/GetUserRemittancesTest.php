<?php

namespace Tests\Feature\Remittance;

use App\Remittance;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetUserRemittancesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_get_list_of_his_remittances_made()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->has(Remittance::factory()->count(5))->create();
        Sanctum::actingAs($user);
        $response = $this->getJson("/api/users/$user->id/remittances");

        $response->assertStatus(200);
        $this->assertCount(5, $response->json()['data']);
    }
}
