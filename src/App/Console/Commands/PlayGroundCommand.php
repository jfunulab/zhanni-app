<?php

namespace App\Console\Commands;

use App\CreditPayment;
use App\DebitPayment;
use App\Jobs\InitiateRemittancePayoutJob;
use App\Jobs\LinkBankAccountToSila;
use App\Jobs\RequestUserSilaKYCJob;
use App\Price;
use Domain\PaymentMethods\Actions\GenerateSilaProcessorTokenAction;
use Domain\PaymentMethods\Actions\IssueSilaAchDebitAction;
use Domain\PaymentMethods\Actions\RegisterUserSilaAccountAction;
use Domain\PaymentMethods\Actions\RequestUserSilaKYCAction;
use Domain\PaymentMethods\Actions\TransferFundsToZhanniAction;
use Domain\PaymentMethods\DTOs\SilaDebitAchData;
use Domain\PaymentMethods\DTOs\TransferToZhanniData;
use Domain\PaymentMethods\Models\BankAccount;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\Users\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Support\PaymentGateway\DTOs\BankTransferData;
use Support\PaymentGateway\Flutterwave\FlutterwaveGateway;
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
    public function handle(): int
    {
        $user = User::find(7);

//        $action = app(RegisterUserSilaAccountAction::class);
        $action = app(RequestUserSilaKYCAction::class);
        ($action)($user);

//        $bankAccount = BankAccount::find(1);
//        dd($bankAccount->toArray());
//        $bankAccount->update(['plaid_data->sila_processor_token' => 'processor-sandbox-82482b35-f9aa-4237-a1b5-dec9b1207b53']);
//        $action = app(GenerateSilaProcessorTokenAction::class);
//        ($action)($bankAccount->plaid_data['access_token'], $bankAccount->account_id);

//        $silaClient = app(SilaClient::class);
//        (new LinkBankAccountToSila($user, $bankAccount))->handle($silaClient);
//        (new RequestUserSilaKYCJob($user))->handle($action);


//        $data = SilaDebitAchData::fromArray([
//            'amount' => 10,
//            'price' => 8.5,
//            'description' => 'test transfer',
////            'account_name' => $bankAccount->account_name
//        ]);
//        $issueDebit = app(IssueSilaAchDebitAction::class);
//        ($issueDebit)($bankAccount, $data);


        /*$transferData = TransferToZhanniData::fromArray([
            'amount' => 1,
            'description' => 'Transfer test transfer money to zhanni'
        ]);
        $transfer = app(TransferFundsToZhanniAction::class);
        ($transfer)($user, $transferData);
        */

//        $flutterwaveGateway = app(FlutterwaveGateway::class);
//        $debitPayment = DebitPayment::create([
//            'uuid' => Str::uuid(),
//            'remittance_id' => 6,
//            'recipient_id' => 1,
//            'amount' => 9,
//            'currency' => 'USD',
//            'status' => 'pending'
//        ]);
//        $recipient = TransferRecipient::find(1);
//        $transferData = BankTransferData::fromArray($user, [
//            'debit_payment' => $debitPayment,
//            'recipient' => $recipient,
//            'description' => 'Testing automated flow.'
//        ]);
//
//        $flutterwaveGateway->disburse($transferData);

//        $initiatePayoutJob = app(InitiateRemittancePayoutJob::class);
//        $creditPayment = CreditPayment::find(4);
//        InitiateRemittancePayoutJob::dispatchSync($creditPayment);
        return 0;
    }
}
