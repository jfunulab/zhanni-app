<?php

namespace Database\Factories;

use Domain\PaymentMethods\Models\Bank;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bank::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'slug' => $this->faker->word,
            'code' => $this->faker->word,
            'type' => $this->faker->word,
            'country' => $this->faker->country,
            'currency' => $this->faker->currencyCode,
            'cash_pickup' => false,
        ];
    }

    public function cash(): BankFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'cash_pickup' => true,
            ];
        });
    }
}
