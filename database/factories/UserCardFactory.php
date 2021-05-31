<?php

namespace Database\Factories;

use Domain\PaymentMethods\Models\UserCard;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserCardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserCard::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Exception
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'platform_id' => $this->faker->word,
            'expiry_month' => random_int(1, 12),
            'expiry_year' => now()->addYears(random_int(1, 6))->year,
            'brand' => $this->faker->word,
            'last_four' => $this->faker->randomNumber(4),
            'postal_code' => $this->faker->postcode
        ];
    }
}
