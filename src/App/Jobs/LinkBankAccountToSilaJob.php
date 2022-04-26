<?php

namespace App\Jobs;

use Domain\PaymentMethods\Actions\LinkBankAccountToSilaAction;
use Domain\PaymentMethods\Models\BankAccount;
use Domain\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LinkBankAccountToSilaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;
    private BankAccount $bankAccount;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, BankAccount $bankAccount)
    {
        $this->user = $user;
        $this->bankAccount = $bankAccount;
    }


    public function handle(LinkBankAccountToSilaAction $linkBankAccountToSilaAction): void
    {
        ($linkBankAccountToSilaAction)($this->user, $this->bankAccount);
    }
}
