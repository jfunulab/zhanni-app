<?php


namespace Domain\Users\Actions;


use App\Jobs\RequestUserSilaKYCJob;
use Domain\Users\Models\User;
use Silamoney\Client\Domain\IdentityAlias;
use Support\PaymentGateway\SilaClient;

class UpdateSilaUserAction
{
    private SilaClient $silaClient;

    /**
     */
    public function __construct(SilaClient $silaClient)
    {
        $this->silaClient = $silaClient;
    }

    /**
     */
    public function __invoke(User $user, array $propertyChanges)
    {

        if(in_array('first_name', $propertyChanges) || in_array('last_name', $propertyChanges) || in_array('birth_date', $propertyChanges)) {
            $response = $this->silaClient->client->updateEntity(
                $user->sila_username,
                $user->sila_key,
                $user->first_name,
                $user->last_name,
                null,
                $user->phone_number
            );
        }

        if(in_array('identity_number', $propertyChanges)) {
            $entityResponse = $this->silaClient->client->getEntity($user->sila_username, $user->sila_key);
            $entityData = json_decode(json_encode($entityResponse->getData()), true);
            $response = $this->silaClient->client->updateIdentity($user->sila_username,
                $user->sila_key,
                $entityData['identities'][0]['uuid'],
                IdentityAlias::SSN(),
                $user->identity_number
            );
        }

        if (isset($response) && $response->getStatusCode() == 200 && !$user->passedKyc()) {
            RequestUserSilaKYCJob::dispatch($user);
        }
    }
}
