<?php

namespace Tests\Unit;

use App\Jobs\SendBankTransfer;
use App\Remittance;
use Domain\PaymentMethods\Models\Bank;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Support\PaymentGateway\Paystack\PaystackGateway;
use Tests\TestCase;

class SendBankTransferTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        $this->markTestSkipped('Pending change in implementation');
    }

    /** test */
    function test_send_a_bank_transfer_when_called()
    {
        $user = User::factory()->create();
        $remittance = Remittance::factory()->create(['user_id' => $user->id]);
        $remittance = Remittance::create([
            'user_id' => $user->id,
            'base_amount' => 10.0,
            'base_currency' => 'USD',
            'amount_to_remit' => 100.0,
            'currency_to_remit' => 'NGN',
        ]);
        $bank = Bank::factory()->create(['code' => '076']);
        $transferRecipient = TransferRecipient::factory()->create([
            'bank_id' => $bank->id,
            'account_number' => '1700106178',
            'account_name' => 'TEMITOPE OLUWAFEMI AJIBULU'
        ]);
        $job = new SendBankTransfer($remittance, $transferRecipient);
        $gateway = $this->app->make(PaystackGateway::class);
        $job->handle($gateway);
    }
}
