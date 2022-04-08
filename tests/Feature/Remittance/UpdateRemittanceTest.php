<?php

namespace Tests\Feature\Remittance;

use App\Remittance;
use Domain\PaymentMethods\Models\BankAccount;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateRemittanceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    function a_remittance_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->newUser()->create();
        Sanctum::actingAs($user);
        $remittance = Remittance::factory()->for($user)->create();
        $recipient = TransferRecipient::factory()->for($user)->create();
        $userBankAccount = BankAccount::factory()->for($user)->create();


        $updateDetails = [
            'amount' => 800,
            'funding_account_id' => $userBankAccount->id,
            'recipient' => $recipient->id
        ];

        $response = $this->putJson("/api/users/$user->id/remittances/$remittance->id", $updateDetails);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Recipient update successful.',
            ]);
    }
}
