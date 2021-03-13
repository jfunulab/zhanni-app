<?php

namespace Tests\Feature\Users;

use App\Notifications\SendPasswordResetCodeNotification;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RequestPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_request_for_email_to_reset_password()
    {
        Notification::fake();
        $user = User::factory()->verified()->create();

        $response = $this->postJson('/api/password/email', [
            'email' => $user->email
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'We have emailed your password reset code!'
            ]);

        Notification::assertSentTo($user, SendPasswordResetCodeNotification::class);
    }

    /** @test */
    function password_reset_link_will_not_be_sent_to_email_that_is_not_registered()
    {
        Notification::fake();

        $response = $this->postJson('/api/password/email', [
            'email' => 'example@email.com'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ["We can't find a user with that email address."]
                ]
            ]);

        Notification::assertNothingSent();
    }
}
