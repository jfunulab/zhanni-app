<?php

namespace App\Jobs;

use App\Remittance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Support\PaymentGateway\DTOs\BankTransferData;

class SendBankTransfer implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var BankTransferData
     */
    private BankTransferData $bankTransferData;
    /**
     * @var Remittance
     */
    private Remittance $remittance;

    /**
     * Create a new job instance.
     *
     * @param Remittance $remittance
     * @param BankTransferData $bankTransferData
     */
    public function __construct(Remittance $remittance, BankTransferData $bankTransferData)
    {
        $this->bankTransferData = $bankTransferData;
        $this->remittance = $remittance;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
