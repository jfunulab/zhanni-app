<?php


namespace Domain\PaymentMethods\Actions;


use Domain\PaymentMethods\DTOs\SilaDebitAchData;
use Domain\PaymentMethods\Models\BankAccount;
use Silamoney\Client\Domain\AchType;
use Support\PaymentGateway\SilaClient;


class IssueSilaAchDebitAction
{

    private SilaClient $silaClient;

    public function __construct(SilaClient $silaClient)
    {
        $this->silaClient = $silaClient;
    }


    public function __invoke(BankAccount $bankAccount, SilaDebitAchData $debitAchData)
    {
        $businessUuid = 'a9f38290-ce34-42db-95ab-630ebba6084a'; // Optional
        $user = $bankAccount->user;
        $response = $this->silaClient->client->issueSila(
            $user->sila_username,
            $debitAchData->amount * 100,
            $debitAchData->accountName,
            $user->sila_key,
            $debitAchData->description,
            $businessUuid,
            AchType::SAME_DAY()
        );
        dump($response);
        dump($response->getData());
    }
}
