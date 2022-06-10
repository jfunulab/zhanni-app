<?php

namespace Tests\Unit;

use Domain\PaymentMethods\Models\Bank;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Support\PaymentGateway\DTOs\BankTransferData;
use Support\PaymentGateway\DTOs\PaymentGatewayTransferRecipientData;
use Support\PaymentGateway\DTOs\ResolvedBankData;
use Support\PaymentGateway\Flutterwave\FlutterwaveGateway;
use Tests\TestCase;

/**
 * @enlighten {"ignore": true}
 */
class FlutterwaveTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var mixed
     */
    private $flutterwaveGateway;

    protected function setUp(): void
    {
        parent::setUp();
        $this->flutterwaveGateway = $this->app->make(FlutterwaveGateway::class);
    }

    /** @test */
    function get_access_token()
    {
        $response = $this->getToken();

        return $response;
    }

    /** @test */
    function disburses_funds()
    {
        $token = $this->getToken();
        $response = Http::log()->withHeaders([
            'Authorization' => $token['token']
        ])->post('https://staging.moneywaveapp.com/v1/disburse', [
            "ref" => "SDWRTY2528423457",
            "amount" => "100",
            "currency" => "USD",
            "bankcode" => "076",
            "accountNumber" => "1763025814",
            "x_recipient_name" => "Testing name",
            "senderName" => "Zhanni",
            "lock" => "Zhanni22MT!",
            "narration" => "Money transfer",
        ]);

    }

    /** @test */
    function can_get_list_of_banks()
    {
        $this->flutterwaveGateway->getBankList();

        $this->assertCount(44, Bank::all());
    }

    /** @test */
    function returns_a_resolved_bank_account_when_receiving_account_is_verified()
    {
//        $transferDataInput = [
//            'account_number' => '0038502584',
//            'bank_code' => '221',
//            'currency' => 'USD'
//        ];
        $transferDataInput = [
            'account_number' => '1763025814',
            'bank_code' => '076',
            'currency' => 'USD'
        ];
        $transferData = BankTransferData::fromArray($transferDataInput);

        $resolvedAccount = $this->flutterwaveGateway->verifyAccountNumber($transferData);
        $this->assertInstanceOf(ResolvedBankData::class, $resolvedAccount);
        $this->assertEquals($transferDataInput['account_number'], $resolvedAccount->accountNumber);
    }

    /**
     * @return array|mixed
     */
    private function getToken()
    {
        //https://live.moneywaveapi.co
        $response = Http::post('https://staging.moneywaveapp.com/v1/merchant/verify', [
            'apiKey' => config('services.flutterwave.moneywave_key'),
            'secret' => config('services.flutterwave.moneywave_secret'),
        ])->json();

        return $response;
    }
}
