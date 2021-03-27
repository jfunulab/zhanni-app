<?php

namespace Tests\Unit\User;

use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @enlighten {"ignore": true}
 */
class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_is_verified_if_email_verified_date_is_not_null()
    {
        $user = User::factory()->verified()->create();

        $this->assertTrue($user->verified);
    }
}
