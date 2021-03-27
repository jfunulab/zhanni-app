<?php

namespace Tests\Unit\User;

use App\Http\Requests\AddUserAddressRequest;
use Domain\Countries\Models\Country;
use Domain\Countries\Models\CountryState;
use Domain\Users\Actions\AddUserAddressAction;
use Domain\Users\DTOs\UserAddressData;
use Domain\Users\DTOs\UserData;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @enlighten {"ignore": true}
 */
class AddUserAddressActionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var AddUserAddressAction
     */
    private AddUserAddressAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(AddUserAddressAction::class);
    }

    /** @test */
    function can_add_address_to_user_account()
    {
        $user = User::factory()->newUser()->create();
        $addressDetails = [
            'country' => Country::factory()->create()->name,
            'state' => CountryState::factory()->create()->name,
            'address_line_one' => $this->faker->address,
            'address_line_two' => $this->faker->streetAddress,
            'postal_code' => $this->faker->postcode
        ];
        $request = new AddUserAddressRequest($addressDetails);

        $userAddressData = UserData::fromRequest($request);
        $userAddress = ($this->action)($user, $userAddressData);

        $this->assertNotNull($userAddress);
        $this->assertEquals($addressDetails['address_line_one'], $userAddress->line_one);
        $this->assertEquals($addressDetails['address_line_two'], $userAddress->line_two);
        $this->assertEquals($addressDetails['postal_code'], $userAddress->postal_code);
        $this->assertEquals($addressDetails['country'], $userAddress->country->name);
        $this->assertEquals($addressDetails['state'], $userAddress->state->name);
    }
}
