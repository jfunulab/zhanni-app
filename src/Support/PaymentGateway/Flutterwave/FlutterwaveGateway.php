<?php


namespace Support\PaymentGateway\Flutterwave;


use App\DebitPayment;
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

    public function disburse(BankTransferData $bankTransferData)
    {
        $isCashPickup = $bankTransferData->debitPayment->remittance->isCashPickup();
        $disburseData = [
            "cashpickup" => $isCashPickup,
            "ref" => $bankTransferData->debitPayment->uuid,
            "amount" => $bankTransferData->debitPayment->amount,
            "currency" => $bankTransferData->debitPayment->currency,
            "bankcode" => $bankTransferData->recipient->bank->code,
            "accountNumber" => (!$isCashPickup) ? $bankTransferData->recipient->account_number : null,
            "x_recipient_name" => $bankTransferData->recipient->account_name,
            "x_recipient_email" => $bankTransferData->recipient->email,
            "x_recipient_phone" => $bankTransferData->recipient->phone_number,
            "senderName" => $bankTransferData->sender->full_name,
            "lock" => config('services.flutterwave.moneywave_lock'),
            "narration" => $bankTransferData->description,
        ];

        $response = $this->client->post('/disburse', $disburseData);

        if($response['status'] == 'success'){
            $bankTransferData->debitPayment->update(['reference_id' => $response['data']['data']['uniquereference']]);
        }

        $this->getDisburseStatus($bankTransferData->debitPayment);
    }

    public function getDisburseStatus(DebitPayment $debitPayment)
    {
        $response = $this->client->post('/disburse/status', [
            'ref' => $debitPayment->uuid
        ]);

        if($response['status'] == 'success'){
            $updateDetails = [
                'status' => $response['data']['status']
            ];
            if(is_null($debitPayment->reference_id)) $updateDetails['reference_id'] = $response['data']['flutterReference'];

            $debitPayment->update($updateDetails);
        }
    }

    public function verifyAccountNumber(BankTransferData $transferData): ?ResolvedBankData
    {
        $response = $this->client->post('resolve/account', [
            'account_number' => $transferData->accountNumber,
            'bank_code' => $transferData->bankCode,
            'currency' => $transferData->currency
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
        $response = $this->client->post('banks?country=NG');
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
