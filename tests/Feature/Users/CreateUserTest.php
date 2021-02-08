<?php

namespace Tests\Feature\Users;

use Domain\Users\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
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

    /** @test */
    function fires_user_registered_event_when_user_is_created_successfully()
    {
        Event::fake();
        $this->withoutExceptionHandling();

        $response = $this->postJson('/api/users', [
            'email' => $this->faker->email,
        ]);

        $response->assertStatus(201);

        Event::assertDispatched(Registered::class);
    }
}
