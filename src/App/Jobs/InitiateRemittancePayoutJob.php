<?php

namespace App\Jobs;

use App\CreditPayment;
use App\DebitPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Support\PaymentGateway\DTOs\BankTransferData;
use Support\PaymentGateway\Flutterwave\FlutterwaveGateway;

class InitiateRemittancePayoutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private CreditPayment $creditPayment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CreditPayment $creditPayment)
    {
        $this->creditPayment = $creditPayment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(FlutterwaveGateway $flutterwaveGateway)
    {
        $remittance = $this->creditPayment->remittance;
        $sender = $remittance->user;
        $recipient = $remittance->recipient;
        $exchangeRate = $remittance->exchangeRate;

        $debitPayment = $remittance->debitPayment()->create([
            'remittance_id' => $remittance->id,
            'credit_payment_reference' => $this->creditPayment->reference_id,
            'recipient_id' => $remittance->recipient->id,
            'amount' => $remittance->base_amount * $exchangeRate->rate,
            'currency' => $exchangeRate->currency,
            'status' => 'pending'
        ]);

        $transferData = BankTransferData::fromArray($sender, [
            'debit_payment' => $debitPayment,
            'recipient' => $recipient,
            'description' => $remittance->reason
        ]);

        $flutterwaveGateway->disburse($transferData);
    }
}
