<?php

namespace Database\Factories;

use Domain\Countries\Models\Country;
use Domain\Countries\Models\CountryState;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryStateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CountryState::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'country_id' => Country::factory(),
            'name' => $this->faker->state,
            'code' => 'NY'
        ];
    }
}
