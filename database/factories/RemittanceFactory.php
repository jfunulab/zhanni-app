<?php

namespace Database\Factories;

use App\CreditPayment;
use App\DebitPayment;
use App\Remittance;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RemittanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Remittance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'recipient_id' => TransferRecipient::factory(),
            'base_amount' => $this->faker->randomDigitNotNull,
            'base_currency' => $this->faker->currencyCode,
            'amount_to_remit' => $this->faker->randomDigitNotNull,
            'currency_to_remit' => $this->faker->currencyCode,
        ];
    }

    public function configure(): RemittanceFactory
    {
        return $this->afterCreating(function($remittance){
            CreditPayment::factory()->for($remittance)->create();
            DebitPayment::factory()->for($remittance)->create();
        });
    }
}
