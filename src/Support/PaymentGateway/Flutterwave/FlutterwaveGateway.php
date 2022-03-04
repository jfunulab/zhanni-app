<?php


namespace Support\PaymentGateway\Flutterwave;


use Domain\PaymentMethods\Models\Bank;
use Support\PaymentGateway\DTOs\BankTransferData;
use Support\PaymentGateway\DTOs\ResolvedBankData;
use Support\PaymentGateway\DTOs\PaymentGatewayTransferRecipientData;
use Support\PaymentGateway\LocalPaymentGateway;
use Support\PaymentGateway\MakesBankTransfer;

class FlutterwaveGateway implements LocalPaymentGateway, MakesBankTransfer
{
    /**
     * @var FlutterwaveClient
     */
    private FlutterwaveClient $client;

    public function __construct(FlutterwaveClient $client)
    {
        $this->client = $client;
    }

    public function transfer(BankTransferData $transferData)
    {
        if($resolvedAccount = $this->verifyAccountNumber($transferData)){
            $transferRecipient = $this->createTransferRecipient($transferData, $resolvedAccount);
            $response = $this->client->post('transfer', [
                'source' => 'balance',
                'reason' => $transferData->description,
                'amount' => $transferData->amount * 100,
                'recipient' => $transferRecipient->recipientCode,
            ]);

            return $response->json() ;
        }
    }

    public function verifyAccountNumber(BankTransferData $transferData): ?ResolvedBankData
    {
        $response = $this->client->post('resolve/account', [
            'account_number' => $transferData->accountNumber,
            'bank_code' => $transferData->bankCode,
            'currency' => $transferData->currency
        ]);
        dump($response);

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
        $response = $this->client->post('banks?country=NG');
        dump($response);
        collect($response['data'])->each(function($bankName, $bankCode){
            $bank = Bank::firstOrNew([
                'code' => $bankCode,
                'country' => 'NG'
            ]);

            $bank->fill([
                'name' => $bankName,
                'currency' => 'NG',
                'pay_with_bank' => true
            ])->save();
        });
    }
}
