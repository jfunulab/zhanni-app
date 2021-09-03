<?php


namespace Domain\PaymentMethods\Actions;


use Carbon\Carbon;
use DateTime;
use Domain\Users\Models\User;
use Illuminate\Support\Str;
use Silamoney\Client\Domain\UserBuilder;
use Support\PaymentGateway\SilaClient;


class RegisterUserSilaAccountAction
{
    private SilaClient $silaClient;
    private RequestUserSilaKYCAction $requestKYCAction;

    public function __construct(SilaClient $silaClient, RequestUserSilaKYCAction $requestKYCAction)
    {
        $this->silaClient = $silaClient;
        $this->requestKYCAction = $requestKYCAction;
    }

    public function __invoke(User $user)
    {
        $silaUsername = Str::random(6)."_".Str::snake($user->first_name).Str::snake($user->last_name);
        $wallet = $this->silaClient->client->generateWallet();
        $builder = new UserBuilder();
        $userData = $builder->handle($silaUsername)
            ->firstName($user->first_name)
            ->lastName($user->last_name)
            ->email($user->email)
            ->phone($user->phone_number)
            ->identityNumber(543212222)
            ->address($user->address->line_one)
            ->city($user->address->city)
            ->state($user->address->state->code)
            ->zipCode($user->address->postal_code)
            ->cryptoAddress($wallet->getAddress())
            ->birthDate(DateTime::createFromFormat('m/d/Y', Carbon::parse($user->birth_date)->format('m/d/Y')))
            ->build();

        $response = $this->silaClient->client->register($userData);
        info($response->getStatusCode());
        info(serialize($response->getData()));
        if ($response->getStatusCode() == 200){
            $user->update([
                'sila_username' => $silaUsername,
                'sila_key' => $wallet->getPrivateKey(),
                'sila_address' => $wallet->getAddress()
            ]);
            ($this->requestKYCAction)($user->fresh());
        }
    }
}
