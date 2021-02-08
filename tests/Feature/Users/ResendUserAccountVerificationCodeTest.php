<?php

namespace Tests\Feature\Users;

use App\Notifications\EmailVerificationNotification;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ResendUserAccountVerificationCodeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_request_verification_code_be_resent_to_email()
    {
        $this->withoutExceptionHandling();
        Notification::fake();
        $user = User::factory()->newUser()->create(['email_verified_at' => null, 'email_verification_code' => 873284]);
        Sanctum::actingAs($user);


        $response = $this->post("/api/email/resend");

        $response->assertStatus(200)->assertJson([
            'message' => 'Verification code sent'
        ]);
        Notification::assertSentTo($user, EmailVerificationNotification::class);
    }

    /** @test */
    function unauthenticated_user_cannot_request_verification_code_to_be_resent_to_email()
    {
        Notification::fake();
        $user = User::factory()->newUser()->create(['email_verified_at' => null, 'email_verification_code' => 873284]);


        $response = $this->postJson("/api/email/resend");

        $response->assertStatus(401);
        Notification::assertNotSentTo($user, EmailVerificationNotification::class);
    }

    /** @test */
    function verified_account_cannot_request_for_for_code_to_be_resent()
    {
        Notification::fake();
        $user = User::factory()->verified()->create(['email_verification_code' => 873284]);
        Sanctum::actingAs($user);


        $response = $this->post("/api/email/resend");

        $response->assertStatus(422)->assertJson([
            'message' => 'Account already verified'
        ]);
        Notification::assertNotSentTo($user, EmailVerificationNotification::class);
    }

}
