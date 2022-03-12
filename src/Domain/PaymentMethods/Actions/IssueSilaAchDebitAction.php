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
        $businessUuid = ''; // Optional
        $user = $bankAccount->user;
        $response = $this->silaClient->client->issueSila(
            $user->sila_username,
            ($debitAchData->amount + $debitAchData->price) * 100,
            $debitAchData->accountName,
            $user->sila_key,
            $debitAchData->description,
            $businessUuid,
            AchType::SAME_DAY()
        );
        info('response from issuing sila debit.');
        info($response);
        info($response->getData());
    }
}
