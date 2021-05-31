<?php

namespace Database\Factories;

use App\DebitPayment;
use App\Remittance;
use Domain\PaymentMethods\Models\TransferRecipient;
use Illuminate\Database\Eloquent\Factories\Factory;

class DebitPaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DebitPayment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'remittance_id' => Remittance::factory(),
            'recipient_id' => TransferRecipient::factory(),
            'amount' => $this->faker->randomDigitNotNull,
            'currency' => $this->faker->currencyCode,
            'status' => 'paid'
        ];
    }
}
