<?php

namespace App\Jobs;

use App\CreditPayment;
use Domain\PaymentMethods\Actions\TransferBetweenSilaWalletsAction;
use Domain\PaymentMethods\DTOs\TransferBetweenSilaWalletsData;
use Domain\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TransferFundsToZhanniWalletJob implements ShouldQueue, ShouldBeUnique
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
    public function handle(TransferBetweenSilaWalletsAction $transferBetweenSilaWalletsAction)
    {
        $zhanniBaseWallet = User::where('email', 'toajibul+depositor@gmail.com ')->first();
        $walletTransferData = TransferBetweenSilaWalletsData::fromArray([
            'amount' => ($this->creditPayment->remittance->base_amount + $this->creditPayment->remittance->fee) * 100,
            'to' => $zhanniBaseWallet,
            'from' => $this->creditPayment->remittance->user,
            'credit_payment' => $this->creditPayment
        ]);

        $response = ($transferBetweenSilaWalletsAction)($walletTransferData);

        if($response->getStatusCode() == 200) {
            $this->creditPayment->update([
                'base_amount_transferred_to_zhanni' => $this->creditPayment->remittance->base_amount * 100,
                'fee_amount_transferred_to_zhanni' => $this->creditPayment->remittance->fee * 100
            ]);
        }
    }
}
