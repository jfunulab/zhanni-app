<?php

namespace Tests\Feature\Remittance;

use App\ExchangeRate;
use App\Http\Requests\AddCardRequest;
use App\Jobs\SendBankTransfer;
use Domain\PaymentMethods\Actions\AddUserCardAction;
use Domain\PaymentMethods\DTOs\UserCardData;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\PaymentMethods\Models\UserCard;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InitiateTransferTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        $this->markTestSkipped('Pending changes in implementation');
    }

    /** @test */
    function a_user_can_initiate_a_transfer_to_a_recipient_from_one_USD_to_other_currencies()
    {
        Queue::fake();
        $this->withoutExceptionHandling();
        $user = $this->setupUserWithCard();
        $recipient = TransferRecipient::factory()->create(['user_id' => $user->id]);
        $rate = ExchangeRate::factory()->create(['rate' => 381.05]);
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/users/$user->id/remittances",[
            'amount' => 50,
            'converted_amount' => 50 * 381.05,
            'reason' => 'For some reason',
            'rate' => $rate->id,
            'card' => $user->cards[0]->id,
            'recipient' => $recipient->id,
        ]);

        $response->assertStatus(201)->assertJson([
            'message' => 'Remittance in progress.',
        ]);
        Queue::assertPushed(SendBankTransfer::class);
    }

    private function setupUserWithCard(){
        $user = User::factory()->create();
        $request = new AddCardRequest([
            'payment_method_id' => getStripeToken()['id'],
            'expiry_month' => 05,
            'expiry_year' => 2022,
            'postal_code' => '004455'
        ]);

        $userCardData = UserCardData::fromRequest($request);
        (new AddUserCardAction)($user, $userCardData);
        return $user->fresh(['cards']);
    }
}
