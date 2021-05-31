<?php

namespace App\Jobs;

use App\Remittance;
use Domain\PaymentMethods\Models\TransferRecipient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Support\PaymentGateway\DTOs\BankTransferData;
use Support\PaymentGateway\LocalPaymentGateway;

class SendBankTransfer implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Remittance
     */
    private Remittance $remittance;
    /**
     * @var TransferRecipient
     */
    private TransferRecipient $transferRecipient;

    /**
     * Create a new job instance.
     *
     * @param Remittance $remittance
     * @param TransferRecipient $transferRecipient
     */
    public function __construct(Remittance $remittance, TransferRecipient $transferRecipient)
    {
        $this->remittance = $remittance;
        $this->transferRecipient = $transferRecipient;
    }

    /**
     * Execute the job.
     *
     * @param LocalPaymentGateway $transferGateway
     * @return void
     */
    public function handle(LocalPaymentGateway $transferGateway)
    {
        $bankTransferData = BankTransferData::fromArray([
            'account_number' => $this->transferRecipient->account_number,
            'bank_code' => $this->transferRecipient->bank->code,
            'amount' => $this->remittance->amount_to_remit,
            'description' => $this->remittance->reason ?? "Transfer from ".$this->remittance->user->full_name,
            'currency' => $this->remittance->currency_to_remit
        ]);

        $result = $transferGateway->transfer($bankTransferData);

        $this->remittance->debitPayment()->create([
            'reference' => $result['data']['reference'],
            'recipient_id' => $this->transferRecipient->id,
            'amount' => $this->remittance->amount_to_remit,
            'currency' => $this->remittance->currency_to_remit,
            'status' => 'paid'
        ]);
    }
}
