<?php

namespace Tests\Feature;

use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use TomorrowIdeas\Plaid\Entities\User as PlaidUser;
use TomorrowIdeas\Plaid\Plaid;

class PlaidTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        $this->markTestSkipped('A playground test class');
    }

    /** @test */
    function creating_a_plaid_link_token()
    {

        try {
            $user = User::factory()->create();
            $plaid = new Plaid(config('services.plaid.client_id'), config('services.plaid.secret'), config('services.plaid.env'));
            $token = $plaid->tokens->create(config('services.plaid.app_name'), "en", ["US"], new PlaidUser($user->id), ["assets", "auth", "identity", "transactions"]);
            dump($token);
        } catch (\Exception $e) {
            dump($e);
        }
    }

    /** @test */
    function generate_access_token_for_user()
    {
        try {
            $plaid = $this->app->make(Plaid::class);
            $accessToken = $plaid->items->exchangeToken('public-sandbox-372658fa-db2e-4b43-a48d-90f62d5cbb8c');
            dump($accessToken);
        } catch (\Exception $e) {
            dump($e);
        }
    }

    /** @test */
    function generate_plaid_sila_token()
    {
        try {
            $plaid = $this->app->make(Plaid::class);
            $accessToken = $plaid->processors->createToken('access-sandbox-ee7a18d3-16b5-4a30-910a-b0fa2ea4151d', 'yng56dgdLjHb3QvkzKMyhxmBryZnjZur7BngX', 'sila_money');
            dump($accessToken);
        } catch (\Exception $e) {
            dump($e);
        }
    }

    /** @test */
    function get_account_balance_test()
    {
        try {
            $plaid = $this->app->make(Plaid::class);
            $balance = $plaid->accounts->getBalance('access-sandbox-ee7a18d3-16b5-4a30-910a-b0fa2ea4151d');
            dump($balance);
        } catch (\Exception $e) {
            dump($e);
        }
    }
}
