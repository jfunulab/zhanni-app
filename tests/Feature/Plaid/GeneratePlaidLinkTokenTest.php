<?php

namespace Tests\Feature\Plaid;

use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeneratePlaidLinkTokenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function generate_plaid_link_token()
    {
        $user = User::factory()->create();


        $response = $this->postJson("api/users/$user->id/plaid-link-token");

        $response->assertSuccessful();
    }
}
