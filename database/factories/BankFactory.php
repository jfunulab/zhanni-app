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
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'slug' => $this->faker->word,
            'code' => $this->faker->word,
            'type' => $this->faker->word,
            'country' => $this->faker->country,
            'currency' => $this->faker->currencyCode,
        ];
    }
}
