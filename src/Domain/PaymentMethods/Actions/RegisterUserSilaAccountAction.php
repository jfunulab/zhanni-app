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
        if (!$this->validateUser($user)) {
           return;
        }
        $silaUsername = Str::random(6) . "_" . Str::snake($user->first_name) . Str::snake($user->last_name);
        $wallet = $this->silaClient->client->generateWallet();
        $builder = new UserBuilder();
        $userData = $builder->handle($silaUsername)
            ->firstName($user->first_name)
            ->lastName($user->last_name)
            ->email($user->email)
            ->phone($user->phone_number)
            ->identityNumber($user->identity_number)
            ->address($user->address->line_one)
            ->city($user->address->city)
            ->state($user->address->state->code)
            ->zipCode($user->address->postal_code)
            ->cryptoAddress($wallet->getAddress())
            ->birthDate(DateTime::createFromFormat('m/d/Y', Carbon::parse($user->birth_date)->format('m/d/Y')))
            ->build();

        $response = $this->silaClient->client->register($userData);

        if ($response->getStatusCode() == 200) {
            $user->update([
                'sila_username' => $silaUsername,
                'sila_key' => $wallet->getPrivateKey(),
                'sila_address' => $wallet->getAddress()
            ]);
            ($this->requestKYCAction)($user->fresh());
        } else {
            $responseData = json_decode(json_encode($response->getData()), true);
            info($responseData);
            if(isset($responseData['validation_details'])){
                $user->update(['kyc_issues' => $responseData['validation_details']]);
            }
        }
    }

    private function validateUser($user): bool
    {
        $issues = [];
        if (is_null($user->phone_number)) {
            $issues['phone_number'] = 'Phone number needs to be provided';
        }

        if (is_null($user->identity_number)) {
            $issues['identity'] = 'Identity number needs to be provided';
        }

        if (is_null($user->birth_date)) {
            $issues['birth_date'] = 'Birth date needs to be provided';
        }

        if (is_null($user->address)) {
            $issues['address'] = 'Address needs to be provided';
        }

        if (is_null($user->address->city)) {
            $issues['city'] = 'City needs to be provided';
        }

        if (is_null($user->address->postal_code)) {
            $issues['postal_code'] = 'Address needs to be provided';
        }

        info($issues);

        $user->update(['kyc_issues' => count($issues) > 0 ? $issues : null]);

        return count($issues) == 0;
    }
}
