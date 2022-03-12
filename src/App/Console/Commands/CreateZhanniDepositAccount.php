<?php

namespace App\Console\Commands;

use Domain\PaymentMethods\Actions\RegisterUserSilaAccountAction;
use Domain\Users\Actions\AddUserAddressAction;
use Domain\Users\DTOs\UserAddressData;
use Domain\Users\Models\User;
use Illuminate\Console\Command;

class CreateZhanniDepositAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sila-user:deposit-account';

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
    public function handle(RegisterUserSilaAccountAction $action, AddUserAddressAction $addUserAddressAction): int
    {
        $user = User::create([
            'first_name' => 'Temi',
            'last_name' => 'Ajibulu',
            'email' => 'toajibul@gmail.com ',
            'username' => 'toajibul',
            'email_verified_at' => now(),
            'password' => bcrypt('password5Password$'),
            'identity_number' => '4523165589',
            'phone_number' => '+19197238931',
            'birth_date' => '1985-01-01',
        ]);

        $userAddressData = UserAddressData::fromArray([
            'lineOne' => '209 E Ben White Blvd',
            'lineTwo' => null,
            'country' => 'United States',
            'state' => 'Texas',
            'city' => 'Austin',
            'postalCode' => '78704',
        ]);

        $addUserAddressAction($user, $userAddressData);

        ($action)($user);

        return 0;
    }
}
