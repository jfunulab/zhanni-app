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

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = Http::withToken('FLWSECK_TEST-8ee498786bb8ff4179c935f10fdb7bb2-X')
            ->post('https://api.flutterwave.com/v3/transfers', [
                "account_bank" => "044",
                "account_number" => "0690000040",
                "amount" => 100,
                "narration" => "Akhlm Pstmn Trnsfr xx007",
                "currency" => "NGN",
                "reference" => "akhlm-pstmnpyt-rfxx007_PMCKDU_1",
//                "callback_url" => "https://webhook.site/b3e505b0-fe02-430e-a538-22bbbce8ce0d",
                "debit_currency" => "NGN"
            ])->json();

        dump($response);
    }

    /** @test */
    function get_access_token()
    {
        $response = $this->getToken();

        dump($response);
        return $response;
    }

    /** @test */
    function disburses_funds()
    {
        $token = $this->getToken();
        $response = Http::withHeaders([
            'Authorization' => $token['token']
        ])->post('https://staging.moneywaveapp.com/v1/disburse', [
            "ref" => "SDWRTY2528423457",
            "amount" => "100",
            "currency" => "USD",
            "bankcode" => "076",
            "accountNumber" => "1763025814",
            "senderName" => "Zhanni",
            "lock" => "MTzhanni2021!",
            "narration" => "Money transfer",
        ]);

        dump($response);
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
            'apiKey' => 'cX8yiLEWul4d6dfNwic3bWUILfnFqWnKWcf77yUFf6P3R7GiFC',
            'secret' => 'slFDpRGXCk0UdGohtoZzfaLgXUXeukJPIDHJpeoot1uB830m12',
        ])->json();

        return $response;
    }
}
