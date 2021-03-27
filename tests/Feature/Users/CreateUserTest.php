<?php

namespace Tests\Feature\Users;

use Domain\Countries\Models\Country;
use Domain\Countries\Models\CountryState;
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
            'address_line_one' => $this->faker->streetAddress,
            'address_line_two' => $this->faker->streetName,
            'country' => $this->faker->country,
            'postal_code' => $this->faker->postcode,
            'state' => $this->faker->state,
            'password' => 'passy-word'
        ];
        Country::factory()->create(['name' => $userDetails['country']]);
        CountryState::factory()->create(['name' => $userDetails['state']]);

        $response = $this->postJson('/api/users', $userDetails);

        $response->assertStatus(201);

        $this->assertCount(1, User::all());
        tap(User::with(['address'])->first(), function ($user) use ($userDetails){
            $this->assertEquals($userDetails['full_name'], $user->full_name);
            $this->assertEquals($userDetails['address_line_one'], $user->address->line_one);
            $this->assertEquals($userDetails['address_line_two'], $user->address->line_two);
            $this->assertEquals($userDetails['country'], $user->address->country->name);
            $this->assertEquals($userDetails['state'], $user->address->state->name);
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

        $userDetails = [
            'email' => $this->faker->email,
            'full_name' => $this->faker->name,
            'address_line_one' => $this->faker->streetAddress,
            'address_line_two' => $this->faker->streetName,
            'country' => $this->faker->country,
            'postal_code' => $this->faker->postcode,
            'state' => $this->faker->state,
            'password' => 'passy-word'
        ];
        Country::factory()->create(['name' => $userDetails['country']]);
        CountryState::factory()->create(['name' => $userDetails['state']]);

        $response = $this->postJson('/api/users', $userDetails);

        $response->assertStatus(201);

        Event::assertDispatched(Registered::class);
    }
}
