<?php

namespace Database\Factories;

use App\CreditPayment;
use App\Remittance;
use Domain\PaymentMethods\Models\UserCard;
use Illuminate\Database\Eloquent\Factories\Factory;

class CreditPaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CreditPayment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'remittance_id' => Remittance::factory(),
            'source_id' => UserCard::factory(),
            'amount' => $this->faker->randomDigitNotNull,
            'currency' => $this->faker->currencyCode,
            'status' => 'paid'
        ];
    }
}
