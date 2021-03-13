<?php

namespace Tests\Feature;

use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Support\PasswordReset\DatabaseTokenRepository;
use Tests\TestCase;

class ResetPasswordWithCodeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_reset_his_password_with_digit_code_to_email()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->verified()->create();

        $token = $this->getPasswordToken($user);

        $response = $this->postJson("api/password/reset", [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new_password',
            'password_confirmation' => 'new_password'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Your password has been reset!'
            ]);
    }

    /**
     * @param $user
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function getPasswordToken($user)
    {
        return $this->app->make(DatabaseTokenRepository::class, ['table' => 'password_resets', 'hashKey' => null])->create($user);
    }
}
