<?php

namespace Tests\Unit\User;

use Domain\Countries\Models\Country;
use Domain\Countries\Models\CountryState;
use Domain\Users\Actions\UpdateUserAddressAction;
use Domain\Users\DTOs\UserData;
use Domain\Users\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateUserAddressActionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(UpdateUserAddressAction::class);
    }

    /** @test */
    function can_update_the_address_of_a_user()
    {
        $address = UserAddress::factory()->create();
        $updateDetails = [
            'address_line_one' => $this->faker->streetAddress,
            'address_line_two' => $this->faker->streetName,
            'country' => $this->faker->country,
            'postal_code' => $this->faker->postcode,
            'state' => $this->faker->state,
        ];
        Country::factory()->create(['name' => $updateDetails['country']]);
        CountryState::factory()->create(['name' => $updateDetails['state']]);
        $userData = UserData::fromArray($updateDetails);

        $userAddress = ($this->action)($address, $userData);

        tap($address->fresh(), function($address) use($userAddress, $updateDetails){
            $this->assertNotNull($userAddress);
            $this->assertTrue($address->is($userAddress));
            $this->assertEquals($updateDetails['address_line_one'], $userAddress->line_one);
            $this->assertEquals($updateDetails['address_line_two'], $userAddress->line_two);
            $this->assertEquals($updateDetails['postal_code'], $userAddress->postal_code);
            $this->assertEquals($updateDetails['country'], $userAddress->country->name);
            $this->assertEquals($updateDetails['state'], $userAddress->state->name);
        });
    }
}
