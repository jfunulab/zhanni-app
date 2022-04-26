<?php

namespace Tests\Feature\Users;

use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateUserRecipientTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    function a_user_can_update_a_recipient()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->newUser()->create();
        Sanctum::actingAs($user);
        $recipient = TransferRecipient::factory()->for($user)->create();

        $updateDetails = [
            'email' => $this->faker->email,
            'account_number' => $this->faker->bankAccountNumber
        ];

        $response = $this->putJson("/api/users/$user->id/recipients/$recipient->id", $updateDetails);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Recipient update successful.',
                'data' => [
                    'email' => $updateDetails['email'],
                    'account_number' => $updateDetails['account_number']
                ]
            ]);
    }
}
