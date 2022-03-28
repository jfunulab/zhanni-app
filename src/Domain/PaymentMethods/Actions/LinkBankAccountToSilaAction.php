<?php


namespace Domain\PaymentMethods\Actions;


use Domain\Users\Models\User;
use Silamoney\Client\Domain\PlaidTokenType;
use Support\PaymentGateway\SilaClient;

class LinkBankAccountToSilaAction
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
    public function __invoke(User $user, $bankAccount)
    {
        $response = $this->silaClient->client->linkAccount(
            $user->sila_username,
            $user->sila_key,
            $bankAccount->plaid_data['sila_processor_token'],
            null,
            $bankAccount->account_id,
            PlaidTokenType::PROCESSOR()
        );

        if ($response->getStatusCode() == 200){
            $bankAccount->update(['sila_linked' => true]);

            return $bankAccount;
        }else {
            info('failed to link account');
            info(json_decode(json_encode($response->getData()), true));
        }
    }
}
