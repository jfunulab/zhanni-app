<?php

namespace App\Console\Commands;

use App\Jobs\LinkBankAccountToSila;
use Domain\PaymentMethods\Actions\GenerateSilaProcessorTokenAction;
use Domain\PaymentMethods\Actions\IssueSilaAchDebitAction;
use Domain\PaymentMethods\Actions\TransferFundsToZhanniAction;
use Domain\PaymentMethods\DTOs\SilaDebitAchData;
use Domain\PaymentMethods\DTOs\TransferToZhanniData;
use Domain\PaymentMethods\Models\BankAccount;
use Domain\Users\Models\User;
use Illuminate\Console\Command;
use Support\PaymentGateway\SilaClient;

class PlayGroundCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'playground:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::find(1);
        $bankAccount = BankAccount::find(4);
//        $bankAccount->update(['plaid_data->sila_processor_token' => 'processor-sandbox-82482b35-f9aa-4237-a1b5-dec9b1207b53']);
//        $action = app(GenerateSilaProcessorTokenAction::class);
//        ($action)($bankAccount->plaid_data['access_token'], $bankAccount->account_id);

//        $silaClient = app(SilaClient::class);
//        (new LinkBankAccountToSila($user, $bankAccount))->handle($silaClient);


        $data = SilaDebitAchData::fromArray([
            'amount' => 1,
            'description' => 'test transfer'
        ]);
        $issueDebit = app(IssueSilaAchDebitAction::class);
        ($issueDebit)($bankAccount, $data);


        /*$transferData = TransferToZhanniData::fromArray([
            'amount' => 1,
            'description' => 'Transfer test transfer money to zhanni'
        ]);
        $transfer = app(TransferFundsToZhanniAction::class);
        ($transfer)($user, $transferData);
        */
    }
}
