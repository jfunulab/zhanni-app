<?php

namespace Tests\Feature;

use Domain\Countries\Models\Country;
use Domain\Countries\Models\CountryState;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AddAddressToAccountTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    function a_user_can_add_address_to_his_account()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->newUser()->create();
        Sanctum::actingAs($user);

        $country = Country::factory()->create();
        $state = CountryState::factory()->create();

        $addressDetails = [
            'country_id' => $country->id,
            'state_id' => $state->id,
            'line_one' => $this->faker->address,
            'line_two' => $this->faker->streetAddress,
            'postal_code' => $this->faker->postcode
        ];

        $response = $this->postJson("/api/users/$user->id/address", $addressDetails);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Address successfully saved.',
                'data' => [
                    'country_id' => $addressDetails['country_id'],
                    'state_id' => $addressDetails['state_id'],
                    'line_one' => $addressDetails['line_one'],
                    'line_two' => $addressDetails['line_two'],
                    'postal_code' => $addressDetails['postal_code']
                ]
            ]);

        $this->assertNotNull(1, $user->address);
    }
}
