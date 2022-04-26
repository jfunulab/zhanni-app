<?php

namespace Tests\Feature\Users;

use Domain\PaymentMethods\Models\BankAccount;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetUserBankAccountsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_list_of_his_bank_accounts()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        BankAccount::factory()->count(4)->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->getJson("api/users/$user->id/bank-accounts");

        $response->assertSuccessful();
    }
}
