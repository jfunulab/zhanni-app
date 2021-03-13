<?php

namespace Tests\Unit;



use Support\PaymentGateway\DTOs\BankTransferData;
use Support\PaymentGateway\DTOs\ResolvedBankDetails;
use Support\PaymentGateway\DTOs\TransferRecipient;
use Support\PaymentGateway\Paystack\PaystackGateway;
use Tests\TestCase;

class PaystackTest extends TestCase
{
    /**
     * @var mixed
     */
    private $paystackGateway;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paystackGateway = $this->app->make(PaystackGateway::class);
    }

    /** @test */
    function returns_a_resolved_bank_account_when_receiving_account_is_verified()
    {
        $transferDataInput = [
            'account_number' => '1700106178',
            'bank_code' => '076'
        ];
        $transferData = BankTransferData::fromArray($transferDataInput);

        $resolvedAccount = $this->paystackGateway->verifyAccountNumber($transferData);
        $this->assertInstanceOf(ResolvedBankDetails::class, $resolvedAccount);
        $this->assertEquals($transferDataInput['account_number'], $resolvedAccount->accountNumber);
    }

    /** @test */
    function returns_null_when_receiving_account_is_not_verified()
    {
        $transferDataInput = [
            'account_number' => '0001234567',
            'bank_code' => '058'
        ];
        $transferData = BankTransferData::fromArray($transferDataInput);

        $resolvedAccount = $this->paystackGateway->verifyAccountNumber($transferData);
        $this->assertNull($resolvedAccount);
    }

    /** @test */
    function can_create_transfer_recipient()
    {
        $transferDataInput = [
            'account_number' => '1700106178',
            'bank_code' => '076',
            'currency' => 'NGN'
        ];
        $transferData = BankTransferData::fromArray($transferDataInput);
        $resolvedAccount = ResolvedBankDetails::fromArray([
            'name' => 'TEMITOPE OLUWAFEMI AJIBULU',
            'account_number' => '1700106178'
        ]);

        $transferRecipient = $this->paystackGateway->createTransferRecipient($transferData, $resolvedAccount);
        $this->assertInstanceOf(TransferRecipient::class, $transferRecipient);
    }

    /** @test */
    function can_get_list_of_banks()
    {
        $this->paystackGateway->getBanks();
    }
}
