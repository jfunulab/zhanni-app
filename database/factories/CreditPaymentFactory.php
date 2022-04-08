<?php

namespace Database\Factories;

use App\CreditPayment;
use App\Remittance;
use Domain\PaymentMethods\Models\Bank;
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
    public function definition(): array
    {
        $bank = Bank::factory()->create();

        return [
            'remittance_id' => Remittance::factory(),
            'sourceable_type' => 'bank',
            'sourceable_id' => $bank->id,
            'amount' => $this->faker->randomDigitNotNull,
            'amount_in_cents' => $this->faker->randomDigitNotNull,
            'currency' => $this->faker->currencyCode,
            'status' => 'paid'
        ];
    }
}
