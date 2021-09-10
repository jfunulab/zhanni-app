<?php

namespace Tests\Feature\Remittance;

use App\ExchangeRate;
use App\Jobs\ProcessRemittance;
use Domain\PaymentMethods\Models\BankAccount;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InitiateTransferTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_initiate_a_transfer_to_a_recipient_from_one_USD_to_other_currencies()
    {
        Queue::fake();
        $this->withoutExceptionHandling();

        $user = User::factory()->has(BankAccount::factory(), 'bankAccounts')->create();
        $recipient = TransferRecipient::factory()->create(['user_id' => $user->id]);
        $rate = ExchangeRate::factory()->create(['rate' => 381.05]);
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/users/$user->id/remittances",[
            'amount' => 50,
            'converted_amount' => 50 * 381.05,
            'reason' => 'For some reason',
            'rate' => $rate->id,
            'funding_account_id' => $user->bankAccounts[0]->id,
            'recipient' => $recipient->id,
        ]);

        $response->assertStatus(201)->assertJson([
            'message' => 'Remittance in progress.',
        ]);
        Queue::assertPushed(ProcessRemittance::class);
    }
}
