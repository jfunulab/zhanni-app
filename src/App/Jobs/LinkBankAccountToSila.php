<?php

namespace App\Jobs;

use Domain\PaymentMethods\Models\BankAccount;
use Domain\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Silamoney\Client\Domain\PlaidTokenType;
use Support\PaymentGateway\SilaClient;

class LinkBankAccountToSila implements ShouldQueue
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


    public function handle(SilaClient $silaClient): void
    {
        $response = $silaClient->client->linkAccount(
            $this->user->sila_username,
            $this->user->sila_key,
            $this->bankAccount->plaid_data['sila_processor_token'],
            null,
            $this->bankAccount->account_id,
            PlaidTokenType::PROCESSOR()
        );

        if ($response->getStatusCode() == 200){
            $this->bankAccount->update(['sila_linked' => true]);
        }else {
            info('failed to link account');
            info(json_decode(json_encode($response->getData()), true));
        }
    }
}
