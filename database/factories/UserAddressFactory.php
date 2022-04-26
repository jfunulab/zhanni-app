<?php

namespace Database\Factories;

use Domain\Countries\Models\Country;
use Domain\Countries\Models\CountryState;
use Domain\Users\Models\User;
use Domain\Users\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'country_id' => Country::factory(),
            'state_id' => CountryState::factory(),
            'line_one' => $this->faker->streetName,
            'line_two' => $this->faker->streetName,
            'postal_code' => $this->faker->postcode,
            'city' => $this->faker->city,
        ];
    }
}
