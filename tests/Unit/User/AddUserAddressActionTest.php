<?php

namespace Tests\Unit\User;

use App\Http\Requests\AddUserAddressRequest;
use Domain\Countries\Models\Country;
use Domain\Countries\Models\CountryState;
use Domain\Users\Actions\AddUserAddressAction;
use Domain\Users\DTOs\UserAddressData;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
    function can_add_payment_to_user_as_a_new_customer_on_stripe()
    {
        $user = User::factory()->newUser()->create();
        $addressDetails = [
            'country_id' => Country::factory()->create()->id,
            'state_id' => CountryState::factory()->create()->id,
            'line_one' => $this->faker->address,
            'line_two' => $this->faker->streetAddress,
            'postal_code' => $this->faker->postcode
        ];
        $request = new AddUserAddressRequest($addressDetails);

        $userAddressData = UserAddressData::fromRequest($request);
        $userAddress = ($this->action)($user, $userAddressData);

        $this->assertNotNull($userAddress);
        $this->assertEquals($addressDetails['line_one'], $userAddress->line_one);
        $this->assertEquals($addressDetails['line_two'], $userAddress->line_two);
        $this->assertEquals($addressDetails['postal_code'], $userAddress->postal_code);
        $this->assertEquals($addressDetails['country_id'], $userAddress->country_id);
        $this->assertEquals($addressDetails['state_id'], $userAddress->state_id);
    }
}
