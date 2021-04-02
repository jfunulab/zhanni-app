<?php

namespace Tests\Feature\Users;

use Domain\Users\Models\User;
use Domain\Users\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    function a_user_can_login_with_email_and_password()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()
            ->newUser()
            ->create(['password' => bcrypt('!Qz89mrt')]);

        $user->address()->save(UserAddress::factory()->make());

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => '!Qz89mrt'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'token',
                    'token_type',
                    'user'
                ]
            ])
            ->assertJson([
                'message' => 'Login successful',
                'data' => [
                    'user' => [
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'address' => [
                            'country' => [
                                'name' => $user->address->country->name
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    function a_user_cannot_login_with_a_wrong_email()
    {
        $this->withoutExceptionHandling();

        User::factory()->newUser()->create(['email' => $this->faker->email, 'password' => bcrypt('!Qz89mrt')]);

        $response = $this->postJson('/api/login', [
            'email' => 'wrong-email',
            'password' => '!Qz89mrt'
        ]);
        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid email or password'
            ]);
    }

    /** @test */
    function a_user_cannot_login_with_a_wrong_password()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->newUser()->create(['email' => $this->faker->email, 'password' => bcrypt('!Qz89mrt')]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password'
        ]);
        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid email or password'
            ]);
    }

    /** @test */
    function a_user_is_required_to_provide_email_to_login()
    {
        $this->withoutExceptionHandling();

        User::factory()->newUser()->create();

        $response = $this->postJson('/api/login', [
            'password' => 'right-password'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Invalid email or password',
                'errors' => [
                    'email' => ['The email field is required.']
                ]
            ]);
    }

    /** @test */
    function a_user_is_required_to_provide_password_to_login()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->newUser()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Invalid email or password',
                'errors' => [
                    'password' => ['The password field is required.']
                ]
            ]);
    }
}
