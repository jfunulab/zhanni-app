<?php

namespace App\Console\Commands;

use App\CreditPayment;
use App\DebitPayment;
use App\Jobs\CheckSilaUserKycJob;
use App\Jobs\InitiateRemittancePayoutJob;
use App\Jobs\LinkBankAccountToSilaJob;
use App\Jobs\RequestUserSilaKYCJob;
use App\Price;
use App\Remittance;
use App\VerificationCategory;
use Domain\PaymentMethods\Actions\GenerateSilaProcessorTokenAction;
use Domain\PaymentMethods\Actions\IssueSilaAchDebitAction;
use Domain\PaymentMethods\Actions\RegisterUserSilaAccountAction;
use Domain\PaymentMethods\Actions\RequestUserSilaKYCAction;
use Domain\PaymentMethods\Actions\TransferFundsToZhanniAction;
use Domain\PaymentMethods\DTOs\SilaDebitAchData;
use Domain\PaymentMethods\DTOs\TransferToZhanniData;
use Domain\PaymentMethods\Models\BankAccount;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\Users\Actions\AddUserAddressAction;
use Domain\Users\Actions\CheckSilaUserKycAction;
use Domain\Users\DTOs\UserAddressData;
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
//        $kycIssues = [
//            'Name Mismatch',
//            'Name Not Verified',
//            'Address Not Matched',
//            'Address Not Verified',
//            'DOB Miskey',
//            'DOB Not Verified',
//            'SSN Miskey',
//            'SSN Not Verified'
//        ];
//
//
//        $kycIssuesCategoryMap = [
//            'Name Not Verified' => VerificationCategory::GENERAL,
//            'Address Not Verified' => VerificationCategory::ADDRESS,
//            'DOB Not Verified' => VerificationCategory::BIRTH_DATE,
//            'SSN Not Verified' => VerificationCategory::SSN
//        ];
//
//        dd(array_values(array_intersect_key($kycIssuesCategoryMap, array_flip($kycIssues))));

//        $user = User::find(28);
//        $user->update(['identity_number' => '123452222']);
        $registerUserSilaAccountAction = app(RegisterUserSilaAccountAction::class);
        $addUserAddressAction = app(AddUserAddressAction::class);
//        $user = User::create([
//            'first_name' => 'Foster',
//            'last_name' => 'Alliswell',
//            'email' => 'foster.alliswell+test1@gmail.com',
//            'username' => 'foster_goodsman_test1',
//            'email_verified_at' => now(),
//            'password' => bcrypt('password5Password$'),
//            'identity_number' => '123452222',
//            'phone_number' => '+19197238931',
//            'birth_date' => '1985-01-01',
//        ]);
//        $userAddressData = UserAddressData::fromArray([
//            'address_line_one' => '209 E Ben White Blvd',
//            'address_line_two' => null,
//            'country' => 'United States',
//            'state' => 'Texas',
//            'city' => 'Austin',
//            'postal_code' => '78704',
//        ]);

        $user = User::create([
            'first_name' => 'Fail',
            'last_name' => 'Judas',
            'email' => 'fail.judas+test1@gmail.com',
            'username' => 'fail_judas_test1',
            'email_verified_at' => now(),
            'password' => bcrypt('password5Password$'),
            'identity_number' => '420420420',
            'phone_number' => '+19197238931',
            'birth_date' => '1970-04-20',
        ]);
        $userAddressData = UserAddressData::fromArray([
            'address_line_one' => '420 420th Street',
            'address_line_two' => null,
            'country' => 'United States',
            'state' => 'Texas',
            'city' => 'Austin',
            'postal_code' => '78704',
        ]);

        $addUserAddressAction($user, $userAddressData);
        ($registerUserSilaAccountAction)($user);


//        ($action)($user);
//        CheckSilaUserKycJob::dispatchSync($user);

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
//            'remittance_id' => 11,
//            'recipient_id' => 1,
//            'amount' => 15,
//            'currency' => 'USD',
//            'status' => 'pending'
//        ]);
//        $recipient = TransferRecipient::find(8);
//        $transferData = BankTransferData::fromArray($user, [
//            'debit_payment' => $debitPayment,
//            'recipient' => $recipient,
//            'description' => 'Testing automated flow.'
//        ]);

//        $flutterwaveGateway->disburse($transferData);

//        $initiatePayoutJob = app(InitiateRemittancePayoutJob::class);
//        $creditPayment = CreditPayment::find(4);
//        InitiateRemittancePayoutJob::dispatchSync($creditPayment);
        return 0;
    }
}
