<?php

namespace Database\Factories;

use Domain\Bids\Models\Bid;
use Domain\Bids\Models\BidOrder;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\PaymentMethods\Models\UserCard;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BidOrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BidOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $bid = Bid::factory()->create();
        return [
            'user_id' => User::factory(),
            'bid_id' => $bid->id,
            'seller_id' => $bid->user->id,
            'minimum_amount' => $bid->minimum_amount,
            'maximum_amount' => $bid->maximum_amount,
            'rate' => $bid->rate,
            'origin_currency' => $bid->origin_currency,
            'destination_currency' => $bid->destination_currency,
            'buyer_funding_account_id' => UserCard::factory(),
            'buyer_receiving_account_id' => TransferRecipient::factory()
        ];
    }
}
