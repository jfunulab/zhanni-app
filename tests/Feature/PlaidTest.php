<?php

namespace Tests\Feature;

use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use TomorrowIdeas\Plaid\Entities\User as PlaidUser;
use TomorrowIdeas\Plaid\Plaid;

class PlaidTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function creating_a_plaid_link_token()
    {

//        try {
//            $user = User::factory()->create();
//            $plaid = new Plaid(config('services.plaid.client_id'), config('services.plaid.secret'), config('services.plaid.env'));
//            $token = $plaid->tokens->create(config('services.plaid.app_name'), "en", ["US"], new PlaidUser($user->id), ["assets", "auth", "identity", "transactions"]);
//            dump($token);
//        } catch (\Exception $e) {
//            dump($e);
//        }
    }
}
