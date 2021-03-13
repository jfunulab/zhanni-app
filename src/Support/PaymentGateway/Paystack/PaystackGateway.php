<?php


namespace Support\PaymentGateway\Paystack;


use Support\PaymentGateway\DTOs\BankTransferData;
use Support\PaymentGateway\DTOs\ResolvedBankDetails;
use Support\PaymentGateway\DTOs\TransferRecipient;
use Support\PaymentGateway\LocalPaymentGateway;

class PaystackGateway implements LocalPaymentGateway
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

    public function verifyAccountNumber(BankTransferData $transferData): ?ResolvedBankDetails
    {
        $response = $this->client->get('bank/resolve', [
            'account_number' => $transferData->accountNumber,
            'bank_code' => $transferData->bankCode
        ]);

        if($response->successful()){
            return ResolvedBankDetails::fromArray($response->json()['data']);
        }

        return null;
    }

    public function createTransferRecipient(BankTransferData $bankTransferData, ResolvedBankDetails $resolvedBankDetails)
    {
        $response = $this->client->post('transferrecipient', [
            'type' => 'nuban',
            'name' => $resolvedBankDetails->accountName,
            'account_number' => $resolvedBankDetails->accountNumber,
            'bank_code' => $bankTransferData->bankCode,
            'currency' => $bankTransferData->currency,
        ]);

        if($response->successful()){
            return TransferRecipient::fromArray($response->json()['data']);
        }

        return null;
    }

    public function getBanks()
    {
        $response = $this->client->get('bank');
        dump($response->json());
    }
}
