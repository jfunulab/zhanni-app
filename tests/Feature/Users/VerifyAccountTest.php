<?php

namespace Tests\Feature\Users;

use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class VerifyAccountTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    function a_user_can_verify_their_account_with_code_sent_to_them_via_email()
    {
        $this->withoutExceptionHandling();
        $verificationCode = 873284;
        $user = User::factory()->newUser()->create(['email_verified_at' => null, 'email_verification_code' => $verificationCode]);
        Sanctum::actingAs($user);

        $response = $this->post("/api/email/verify/{$user->id}/$verificationCode");

        $response->assertStatus(200)->assertJson([
            'message' => 'Account verified'
        ]);
    }

    /**
     * @test
     * @enlighten {"ignore": true}
     */
    function a_user_cannot_verify_their_account_with_a_wrong_code()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->newUser()->create(['email_verified_at' => null, 'email_verification_code' => 873284]);
        Sanctum::actingAs($user);

        $response = $this->post("/api/email/verify/{$user->id}/6598432");

        $response->assertStatus(422)->assertJson([
            'message' => 'Invalid verification code'
        ]);
    }

    /**
     * @test
     * @enlighten {"ignore": true}
     */
    function a_user_cannot_verify_their_account_with_expired_code()
    {
        $this->withoutExceptionHandling();
        $verificationCode = 873284;
        $user = User::factory()->newUser()->create(['email_verified_at' => null, 'email_verification_code' => $verificationCode, 'verification_code_expires_at' => now()->subMinutes(61)]);
        Sanctum::actingAs($user);

        $response = $this->post("/api/email/verify/{$user->id}/$verificationCode");

        $response->assertStatus(422)->assertJson([
            'message' => 'Verification code expired'
        ]);
    }

    /**
     * @test
     * @enlighten {"ignore": true}
     */
    function a_user_cannot_verify_account_for_another_user()
    {
        $this->withoutExceptionHandling();
        $verificationCode = 873284;
        $userA = User::factory()->newUser()->create(['email_verified_at' => null, 'email_verification_code' => $verificationCode]);
        $userB = User::factory()->newUser()->create(['email_verified_at' => null, 'email_verification_code' => $verificationCode]);
        Sanctum::actingAs($userA);


        $response = $this->post("/api/email/verify/$userB->id/$verificationCode");

        $response->assertStatus(422)->assertJson([
            'message' => 'Invalid verification code'
        ]);
    }

    /**
     * @test
     * @enlighten {"ignore": true}
     */
    function a_user_cannot_verify_their_account_with_code_sent_to_them_via_email_if_account_is_already_verified()
    {
        $this->withoutExceptionHandling();
        $verificationCode = 873284;
        $user = User::factory()->newUser()->create(['email_verified_at' => now()->subMinutes(30), 'email_verification_code' => $verificationCode]);
        Sanctum::actingAs($user);

        $response = $this->post("/api/email/verify/{$user->id}/$verificationCode");

        $response->assertStatus(422)->assertJson([
            'message' => 'Account already verified'
        ]);
    }

    /**
     * @test
     * @enlighten {"ignore": true}
     */
    function unauthenticated_user_cannot_verify_account()
    {
        $verificationCode = 873284;
        $user = User::factory()->newUser()->create(['email_verified_at' => null, 'email_verification_code' => $verificationCode]);

        $response = $this->postJson("/api/email/verify/$user->id/$verificationCode");

        $response->assertStatus(401);
    }
}
