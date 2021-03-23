<?php


namespace Support\PaymentGateway\Paystack;


use Domain\PaymentMethods\Models\Bank;
use Support\PaymentGateway\DTOs\BankTransferData;
use Support\PaymentGateway\DTOs\ResolvedBankData;
use Support\PaymentGateway\DTOs\PaymentGatewayTransferRecipientData;
use Support\PaymentGateway\LocalPaymentGateway;
use Support\PaymentGateway\MakesBankTransfer;

class PaystackGateway implements LocalPaymentGateway, MakesBankTransfer
{
    /**
     * @var PaystackClient
     */
    private PaystackClient $client;

    public function __construct(PaystackClient $client)
    {
        $this->client = $client;
    }

    public function transfer(BankTransferData $transferData)
    {

    }

    public function verifyAccountNumber(BankTransferData $transferData): ?ResolvedBankData
    {
        $response = $this->client->get('bank/resolve', [
            'account_number' => $transferData->accountNumber,
            'bank_code' => $transferData->bankCode
        ]);

        if($response->successful()){
            return ResolvedBankData::fromArray($response->json()['data']);
        }

        return null;
    }

    public function createTransferRecipient(BankTransferData $bankTransferData, ResolvedBankData $resolvedBankDetails): ?PaymentGatewayTransferRecipientData
    {
        $response = $this->client->post('transferrecipient', [
            'type' => 'nuban',
            'name' => $resolvedBankDetails->accountName,
            'account_number' => $resolvedBankDetails->accountNumber,
            'bank_code' => $bankTransferData->bankCode,
            'currency' => $bankTransferData->currency,
        ]);

        if($response->successful()){
            return PaymentGatewayTransferRecipientData::fromArray($response->json()['data']);
        }

        return null;
    }

    public function getBankList(): void
    {
        $response = $this->client->get('bank')->json();
        collect($response['data'])->each(function($bankData){
            $bank = Bank::firstOrNew([
                'code' => $bankData['code'],
                'slug' => $bankData['slug'],
                'country' => $bankData['country']
            ]);

            $bank->fill([
                'name' => $bankData['name'],
                'currency' => $bankData['currency'],
                'type' => $bankData['type'],
                'pay_with_bank' => $bankData['pay_with_bank']
            ])->save();
        });
    }
}
