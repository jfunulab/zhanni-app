<?php

namespace Database\Factories;

use Domain\PaymentMethods\Models\Bank;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransferRecipientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransferRecipient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'bank_id' => Bank::factory(),
            'email' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'account_name' => $this->faker->name,
            'account_number' => $this->faker->bankAccountNumber
        ];
    }
}
