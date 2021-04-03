<?php

namespace Tests\Feature;

use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetUserRecipientsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_get_list_of_recipients_added_to_account()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->newUser()->create();
        TransferRecipient::factory()->count(2)->create(['user_id' => $user]);
        Sanctum::actingAs($user);

        $response = $this->getJson("api/users/$user->id/recipients");

        $response->assertSuccessful();
        tap($response->decodeResponseJson(), function($response){
            $this->assertCount(2, $response['data']);
        });
    }
}
