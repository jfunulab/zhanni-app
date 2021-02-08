<?php

namespace Tests\Unit;

use App\Notifications\EmailVerificationNotification;
use Domain\Users\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Support\NumericCodeGenerator;
use Tests\TestCase;

class SendEmailVerificationNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function sends_email_with_verification_codes_to_user()
    {
        Mail::fake();
        $verificationCode = 553322;
        $this->mock(NumericCodeGenerator::class, function($mock) use ($verificationCode){
            return $mock->shouldReceive('execute')->once()->andReturn($verificationCode);
        });

        $user = User::factory()->newUser()->create();
        $event = new Registered($user);
        $listener = new SendEmailVerificationNotification();
        $listener->handle($event);

        tap($user->fresh(), function($user) use($verificationCode){
            $this->assertEquals($verificationCode, $user->email_verification_code);
        });
    }
}
