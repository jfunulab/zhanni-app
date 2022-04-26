<?php

namespace Tests\Feature\Remittance;

use App\ExchangeRate;
use Domain\PaymentMethods\Actions\IssueSilaAchDebitAction;
use Domain\PaymentMethods\Models\Bank;
use Domain\PaymentMethods\Models\BankAccount;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Mockery\MockInterface;
use Silamoney\Client\Api\ApiResponse;
use Silamoney\Client\Domain\OperationResponse;
use Tests\TestCase;

class InitiateTransferTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_initiate_a_transfer_to_a_recipient_bank_details()
    {
        $this->withoutExceptionHandling();

        $operationResponseMock = $this->mock(OperationResponse::class, function(MockInterface $mock){
            $mock->shouldReceive('getTransactionId')->once();
        });
        $apiResponseMock = $this->mock(ApiResponse::class, function(MockInterface $mock) use ($operationResponseMock){
            $mock->shouldReceive('getStatusCode')->once()->andReturn(200);
            $mock->shouldReceive('getData')->once()->andReturn($operationResponseMock);
        });

        $this->mock(IssueSilaAchDebitAction::class, function (MockInterface $mock) use ($apiResponseMock) {
            $mock->shouldReceive('__invoke')
                ->once()->andReturn($apiResponseMock);
        });

        $user = User::factory()->has(BankAccount::factory(), 'bankAccounts')->create();
        $recipient = TransferRecipient::factory()->create(['user_id' => $user->id]);
        $rate = ExchangeRate::factory()->create(['rate' => 381.05]);
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/users/$user->id/remittances",[
            'type' => 'bank_details',
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
    }

    /** @test */
    function a_user_can_initiate_a_transfer_to_a_recipient_for_cash_pickup()
    {
        $this->withoutExceptionHandling();

        $operationResponseMock = $this->mock(OperationResponse::class, function(MockInterface $mock){
            $mock->shouldReceive('getTransactionId')->once();
        });
        $apiResponseMock = $this->mock(ApiResponse::class, function(MockInterface $mock) use ($operationResponseMock){
            $mock->shouldReceive('getStatusCode')->once()->andReturn(200);
            $mock->shouldReceive('getData')->once()->andReturn($operationResponseMock);
        });

        $this->mock(IssueSilaAchDebitAction::class, function (MockInterface $mock) use ($apiResponseMock) {
            $mock->shouldReceive('__invoke')
                ->once()->andReturn($apiResponseMock);
        });

        $user = User::factory()->has(BankAccount::factory(), 'bankAccounts')->create();
        $bank = Bank::factory()->cash()->create();
        $recipient = TransferRecipient::factory()->create(['user_id' => $user->id, 'bank_id' => $bank->id]);
        $rate = ExchangeRate::factory()->create(['rate' => 381.05]);
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/users/$user->id/remittances",[
            'type' => 'cash_pickup',
            'amount' => 50,
            'converted_amount' => 50 * 381.05,
            'reason' => 'For some reason',
            'rate' => $rate->id,
            'funding_account_id' => $user->bankAccounts[0]->id,
            'recipient' => $recipient->id,
            'pickup_bank_id' => $recipient->bank_id
        ]);

        $response->assertStatus(201)->assertJson([
            'message' => 'Remittance in progress.',
        ]);
    }
}
