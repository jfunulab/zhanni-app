<?php


namespace Domain\PaymentMethods\Actions;


use Domain\PaymentMethods\DTOs\TransferToZhanniData;
use Domain\Users\Models\User;
use Support\PaymentGateway\SilaClient;


class TransferFundsToZhanniAction
{

    private SilaClient $silaClient;

    public function __construct(SilaClient $silaClient)
    {
        $this->silaClient = $silaClient;
    }


    public function __invoke(User $user, TransferToZhanniData $transferToZhanniData)
    {
        $response = $this->silaClient->client->transferSila(
            $user->sila_username,
            $transferToZhanniData->zhanniHandle,
            $transferToZhanniData->amount * 100,
            $user->sila_key,
            null,
            null,
            $transferToZhanniData->description,
            'a9f38290-ce34-42db-95ab-630ebba6084a'
        );
        dump($response);
        dump($response->getData());
    }
}
