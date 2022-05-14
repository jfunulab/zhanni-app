<?php


namespace Domain\PaymentMethods\Actions;



use Domain\Users\Models\User;
use Support\PaymentGateway\SilaClient;


class RequestUserSilaKYCAction
{
    private SilaClient $silaClient;

    public function __construct(SilaClient $silaClient)
    {
        $this->silaClient = $silaClient;
    }

    public function __invoke(User $user)
    {
        $kycLevel = 'DEFAULT';
        $response = $this->silaClient->client->requestKYC($user->sila_username, $user->sila_key, $kycLevel);

        if($response->getStatusCode() == 200){
            $user->update(['kyc_status' => 'in review']);
        }
    }
}
