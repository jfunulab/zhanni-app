<?php

namespace Tests\Feature\Users;

use Domain\Countries\Models\Country;
use Domain\Countries\Models\CountryState;
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

    /** @test */
    function a_user_can_update_his_username()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->newUser()->create();
        Sanctum::actingAs($user);

        $updateDetails = [
            'username' => $this->faker->userName,
        ];
        $response = $this->putJson("/api/users/$user->id", $updateDetails);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User update successful.',
                'data' => [
                    'user' => [
                        'username' => $updateDetails['username']
                    ]
                ]
            ]);
    }

    /** @test */
    function a_user_can_update_his_birth_date()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->newUser()->create();
        Sanctum::actingAs($user);

        $updateDetails = [
            'birth_date' => '1993-06-01',
        ];
        $response = $this->putJson("/api/users/$user->id", $updateDetails);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User update successful.',
                'data' => [
                    'user' => [
                        'birth_date' => $updateDetails['birth_date']
                    ]
                ]
            ]);
    }

    /** @test */
    function a_user_can_update_his_address()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->newUser()->create();
        Sanctum::actingAs($user);

        $updateDetails = [
            'address_line_one' => $this->faker->streetAddress,
            'address_line_two' => $this->faker->streetName,
            'country' => $this->faker->country,
            'postal_code' => $this->faker->postcode,
            'state' => $this->faker->state,
            'city' => $this->faker->city,
        ];
        Country::factory()->create(['name' => $updateDetails['country']]);
        CountryState::factory()->create(['name' => $updateDetails['state']]);

        $response = $this->putJson("/api/users/$user->id", $updateDetails);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User update successful.',
                'data' => [
                    'user' => [
                        'address' => [
                            'line_one' => $updateDetails['address_line_one'],
                            'line_two' => $updateDetails['address_line_two'],
                            'country' => [
                                'name' => $updateDetails['country']
                            ],
                            'state' => [
                                'name' => $updateDetails['state']
                            ]
                        ]
                    ]
                ]
            ]);

        tap(User::with(['address'])->first(), function ($user) use ($updateDetails){
            $this->assertEquals($updateDetails['address_line_one'], $user->address->line_one);
            $this->assertEquals($updateDetails['address_line_two'], $user->address->line_two);
            $this->assertEquals($updateDetails['country'], $user->address->country->name);
            $this->assertEquals($updateDetails['state'], $user->address->state->name);
            $this->assertEquals($updateDetails['city'], $user->address->city);
        });
    }
}
