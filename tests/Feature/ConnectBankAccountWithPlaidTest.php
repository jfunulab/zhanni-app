<?php

namespace Tests\Feature;

use Domain\PaymentMethods\Actions\GeneratePlaidAccessTokenAction;
use Domain\PaymentMethods\Actions\GenerateSilaProcessorTokenAction;
use Domain\PaymentMethods\Actions\RegisterUserSilaAccountAction;
use Domain\Users\Models\User;
use Domain\Users\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Mockery\MockInterface;
use Tests\TestCase;

class ConnectBankAccountWithPlaidTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_connect_a_bank_account_with_plaid()
    {
        Queue::fake();
        $this->withoutExceptionHandling();

        $user = User::factory()->newUser()->has(UserAddress::factory()->count(1), 'address')->create(['phone_number' => '9876543210']);
        Sanctum::actingAs($user);

        $this->mock(GeneratePlaidAccessTokenAction::class, function(MockInterface $mock){
            $mock->shouldReceive('__invoke')->once()->andReturn('test-plaid-access-token');
        });

        $this->mock(GenerateSilaProcessorTokenAction::class, function(MockInterface $mock){
            $mock->shouldReceive('__invoke')->once();
        });

        $this->mock(RegisterUserSilaAccountAction::class, function(MockInterface $mock){
            $mock->shouldReceive('__invoke')->once();
        });

        $plaidAccountDetails = [
            'account_id' => 'yng56dgdLjHb3QvkzKMyhxmBryZnjZur7BngX',
            'account_name' => 'Checking',
            'institution_name' => 'Citibank Online',
            'institution_id' => 'ins_5',
            'plaid_public_token' => 'public-sandbox-372658fa-db2e-4b43-a48d-90f62d5cbb8c',
        ];

        $response = $this->postJson("/api/users/$user->id/plaid-bank-accounts", $plaidAccountDetails);

        $response->assertSuccessful();
    }

    /**
     * @test
     * @enlighten {"ignore": true}
     */
    function user_cannot_connect_a_bank_account_if_user_has_no_phone_number()
    {
        Queue::fake();

        $user = User::factory()->newUser()->has(UserAddress::factory()->count(1), 'address')
            ->create(['phone_number' => null]);
        Sanctum::actingAs($user);

        $plaidAccountDetails = [
            'account_id' => 'yng56dgdLjHb3QvkzKMyhxmBryZnjZur7BngX',
            'account_name' => 'Checking',
            'institution_name' => 'Citibank Online',
            'institution_id' => 'ins_5',
            'plaid_public_token' => 'public-sandbox-372658fa-db2e-4b43-a48d-90f62d5cbb8c',
        ];

        $response = $this->postJson("/api/users/$user->id/plaid-bank-accounts", $plaidAccountDetails);

        $response->assertStatus(422);
    }

    /**
     * @test
     * @enlighten {"ignore": true}
     */
    function user_cannot_connect_a_bank_account_if_user_has_no_identity_number()
    {
        Queue::fake();

        $user = User::factory()->newUser()->has(UserAddress::factory()->count(1), 'address')
            ->create(['identity_number' => null]);
        Sanctum::actingAs($user);

        $plaidAccountDetails = [
            'account_id' => 'yng56dgdLjHb3QvkzKMyhxmBryZnjZur7BngX',
            'account_name' => 'Checking',
            'institution_name' => 'Citibank Online',
            'institution_id' => 'ins_5',
            'plaid_public_token' => 'public-sandbox-372658fa-db2e-4b43-a48d-90f62d5cbb8c',
        ];

        $response = $this->postJson("/api/users/$user->id/plaid-bank-accounts", $plaidAccountDetails);

        $response->assertStatus(422);
    }
}
