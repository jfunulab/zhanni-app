<?php

namespace Tests\Feature\Users;

use Domain\PaymentMethods\Models\Bank;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AddRecipientToUserAccountTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    function a_user_can_save_a_transfer_recipient_in_his_account()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->newUser()->create();
        Sanctum::actingAs($user);

        $recipientDetails = [
            'email' => $this->faker->email,
            'account_name' => $this->faker->name,
            'account_number' => $this->faker->bankAccountNumber,
            'bank_id' => Bank::factory()->create()->id
        ];

        $response = $this->postJson("/api/users/$user->id/recipients", $recipientDetails);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Recipient successfully saved.',
                'data' => [
                    'email' => $recipientDetails['email'],
                ]
            ]);

        $this->assertCount(1, $user->recipients);
    }
}
