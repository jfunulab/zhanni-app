<?php

namespace Tests\Feature\Users;

use Domain\Users\Models\User;
use Domain\Users\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetUserProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_make_request_for_his_own_profile_data()
    {
        $this->withoutExceptionHandling();

        $users = User::factory()->has(UserAddress::factory(), 'address')->count(5)->create();
        Sanctum::actingAs($users[3]);

        $response = $this->getJson('api/user');
        $response->assertSuccessful()->assertJson([
            'data' => [
                'id' => $users[3]->id
            ]
        ]);
    }
}
