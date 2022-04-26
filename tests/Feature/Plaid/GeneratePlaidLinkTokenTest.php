<?php

namespace Tests\Feature\Plaid;

use Domain\PaymentMethods\Actions\GeneratePlaidLinkTokenAction;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class GeneratePlaidLinkTokenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function generate_plaid_link_token()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->mock(GeneratePlaidLinkTokenAction::class, function(MockInterface $mock){
            $mock->shouldReceive('__invoke')->once();
        });


        $response = $this->postJson("api/users/$user->id/plaid-link-token");

        $response->assertSuccessful();
    }
}
