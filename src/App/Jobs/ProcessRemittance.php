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
use Support\PaymentGateway\LocalPaymentGateway;

class ProcessRemittance implements ShouldQueue, ShouldBeUnique
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
     */
    public function __construct(Remittance $remittance)
    {
        $this->remittance = $remittance;
    }

    /**
     * Execute the job.
     *
     * @param LocalPaymentGateway $transferGateway
     * @return void
     */
    public function handle(LocalPaymentGateway $transferGateway)
    {
//        $bankTransferData = BankTransferData::fromArray([
//            'account_number' => $this->transferRecipient->account_number,
//            'bank_code' => $this->transferRecipient->bank->code,
//            'amount' => $this->remittance->amount_to_remit,
//            'description' => $this->remittance->reason ?? "Transfer from ".$this->remittance->user->full_name,
//            'currency' => $this->remittance->currency_to_remit
//        ]);
//
//        $result = $transferGateway->transfer($bankTransferData);
//
//        $this->remittance->debitPayment()->create([
//            'reference' => $result['data']['reference'],
//            'recipient_id' => $this->transferRecipient->id,
//            'amount' => $this->remittance->amount_to_remit,
//            'currency' => $this->remittance->currency_to_remit,
//            'status' => 'paid'
//        ]);
    }
}
