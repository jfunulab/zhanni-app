<?php

namespace Database\Factories;

use Domain\Bids\Models\Bid;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BidFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bid::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'minimum_amount' => $this->faker->randomDigitNotNull,
            'maximum_amount' => $this->faker->randomDigitNotNull,
            'rate' => $this->faker->randomDigitNotNull,
            'origin_currency' => $this->faker->currencyCode,
            'destination_currency' => $this->faker->currencyCode
        ];
    }
}
