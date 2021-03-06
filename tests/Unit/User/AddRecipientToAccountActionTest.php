<?php

namespace Tests\Unit\User;

use App\Http\Requests\AddRecipientToAccountRequest;
use Domain\PaymentMethods\Actions\AddRecipientToAccountAction;
use Domain\PaymentMethods\DTOs\RemittanceData;
use Domain\PaymentMethods\DTOs\TransferRecipientData;
use Domain\PaymentMethods\Models\Bank;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @enlighten {"ignore": true}
 */
class AddRecipientToAccountActionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var AddRecipientToAccountAction
     */
    private $action;

    protected function setUp(): void
    {
        $this->markTestSkipped('Pending change in implementation.');

        parent::setUp();
        $this->action = app(AddRecipientToAccountAction::class);
    }

    /** @test */
    function adds_recipient_information_to_user_account()
    {
        $user = User::factory()->newUser()->create();
        $recipientDetails = [
            'email' => getStripeToken()['id'],
            'account_name' => $this->faker->name,
            'account_number' => $this->faker->bankAccountNumber,
            'bank_id' => Bank::factory()->create()->id
        ];
        $request = new AddRecipientToAccountRequest($recipientDetails);
        $recipientData = TransferRecipientData::fromRequest($request);

        $recipient = ($this->action)($user, $recipientData);

        $this->assertNotNull($recipient);
        $this->assertEquals($recipientDetails['email'], $recipient->email);
        $this->assertEquals($recipientDetails['account_name'], $recipient->account_name);
        $this->assertEquals($recipientDetails['account_number'], $recipient->account_number);
        $this->assertEquals($recipientDetails['bank_id'], $recipient->bank_id);
    }
}
