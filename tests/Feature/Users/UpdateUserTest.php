<?php

namespace Tests\Feature\Users;

use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    function a_user_can_update_his_phone_number()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->newUser()->create();
        Sanctum::actingAs($user);

        $updateDetails = [
            'phone_number' => $this->faker->phoneNumber,
        ];
        $response = $this->putJson("/api/users/$user->id", $updateDetails);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User update successful.',
                'data' => [
                    'user' => [
                        'phone_number' => $updateDetails['phone_number']
                    ]
                ]
            ]);
    }
}
