<?php


namespace Domain\Users\Actions;


use App\Jobs\RequestUserSilaKYCJob;
use Carbon\Carbon;
use DateTime;
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
                DateTime::createFromFormat('m/d/Y', Carbon::parse($user->birth_date)->format('m/d/Y'))
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

        if(
            in_array('country_id', $propertyChanges)
            || in_array('state_id', $propertyChanges)
            || in_array('line_one', $propertyChanges)
            || in_array('line_two', $propertyChanges)
            || in_array('city', $propertyChanges)
            || in_array('postal_code', $propertyChanges)
        ){
            info('updating sila user address.');
            $entityResponse = $this->silaClient->client->getEntity($user->sila_username, $user->sila_key);
            $entityData = json_decode(json_encode($entityResponse->getData()), true);
            info($entityData);
            $address = $user->address;
            $response = $this->silaClient->client->updateAddress(
                $user->sila_username,
                $user->sila_key,
                $entityData['addresses'][0]['uuid'],
                null,
                $address->line_one,
                $address->city,
                $address->state->code,
                null,
                $address->postal_code,
            );
        }

        if (isset($response) && $response->getStatusCode() == 200 && !$user->passedKyc()) {
            RequestUserSilaKYCJob::dispatch($user);
        }
    }
}
