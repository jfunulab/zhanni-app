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
        $userDetails = [
            'email' => $this->faker->email,
            'full_name' => $this->faker->name,
            'address' => $this->faker->address,
            'password' => 'passy-word'
        ];
        $response = $this->postJson('/api/users', $userDetails);

        $response->assertStatus(201);

        $this->assertCount(1, User::all());
        tap(User::first(), function ($user) use ($userDetails){
            $this->assertEquals($userDetails['full_name'], $user->full_name);
            $this->assertEquals($userDetails['address'], $user->address);
        });
    }

    /**
     * @test
     * @enlighten {"ignore": true}
     */
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
