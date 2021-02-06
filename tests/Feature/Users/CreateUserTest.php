<?php

namespace Tests\Feature\Users;

use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Throwable;

class CreateUserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    function a_user_can_create_an_account()
    {
        $this->withoutExceptionHandling();
        $response = $this->postJson('/api/users', [
            'email' => $this->faker->email,
        ]);

        $response->assertStatus(201);

        $this->assertCount(1, User::all());
    }
}
