<?php

namespace Database\Factories;

use Domain\PaymentMethods\Models\BankAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BankAccount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'account_name' => $this->faker->word,
            'account_id' => $this->faker->word
        ];
    }
}
