<?php

namespace Database\Factories;

use Domain\PaymentMethods\Models\TransferRecipient;
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
    public function definition()
    {
        return [
            //
        ];
    }
}
