<?php

namespace Tests\Feature\Plaid;

use App\Notifications\PlaidSilaTokenNotification;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DumpPlaidSilaTokenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function endpoint_to_dump_plaid_sila_token()
    {
        Notification::fake();

        $user = User::factory()->create();
        $token = Str::random();

        Sanctum::actingAs($user);

        $response = $this->postJson("api/users/$user->id/plaid-sila-token", [
            'token' => $token
        ]);

        $response->assertSuccessful();
        Notification::assertSentToTimes( $user, PlaidSilaTokenNotification::class, 1);
    }
}
