<?php


namespace Domain\PaymentMethods\Actions;


use App\Exceptions\BankConnectionException;
use Domain\Users\Models\User;

class UserCanConnectPlaidAccountAction
{
    private array $errors = [];

    public function __invoke(User $user): bool
    {
        if(!$user->phone_number) {
            $this->errors = array_merge($this->errors, ['phone_number' => ['Provide phone number to connect account']]);
        }

        if(!$user->birth_date) {
            $this->errors = array_merge($this->errors, ['birth_date' => ['Provide birth date to connect account']]);
        }

        if(count($this->errors) > 0){
            throw new BankConnectionException("Bank connection validation failed", $this->errors);
        }

        return true;
    }
}
