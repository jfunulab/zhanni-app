<?php

namespace App\Console\Commands;

use App\Jobs\LinkBankAccountToSilaJob;
use Domain\PaymentMethods\Models\BankAccount;
use Domain\Users\Models\User;
use Illuminate\Console\Command;

class ReLinkBankAccountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bank-account:link {--user=} {--bankAccount=}';

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
        $user = User::find($this->option('user'));
        $bankAccount = BankAccount::find($this->option('bankAccount'));

        if($user && $bankAccount) {
            LinkBankAccountToSilaJob::dispatchSync($user, $bankAccount);
            $this->info('Account linked successfully.');
        }else {
            $this->error('Wrong inputs.');
        }
        return 0;
    }
}
