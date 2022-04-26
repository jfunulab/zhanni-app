<?php


namespace Domain\PaymentMethods\Actions;


use Domain\PaymentMethods\DTOs\SilaDebitAchData;
use Domain\PaymentMethods\DTOs\TransferBetweenSilaWalletsData;
use Domain\PaymentMethods\Models\BankAccount;
use Silamoney\Client\Api\ApiResponse;
use Silamoney\Client\Domain\AchType;
use Support\PaymentGateway\SilaClient;


class TransferBetweenSilaWalletsAction
{

    private SilaClient $silaClient;

    public function __construct(SilaClient $silaClient)
    {
        $this->silaClient = $silaClient;
    }


    public function __invoke(TransferBetweenSilaWalletsData $silaWalletsTransferData): ApiResponse
    {
        info('invoking sila transfer');
        info($silaWalletsTransferData->to->toArray());
        info($silaWalletsTransferData->from->toArray());
        $response = $this->silaClient->client->transferSila(
            $silaWalletsTransferData->from->sila_username,
            $silaWalletsTransferData->to->sila_username,
            $silaWalletsTransferData->amount,
            $silaWalletsTransferData->from->sila_key,
            $silaWalletsTransferData->to->sila_address,
        );


        info($response->getStatusCode());
        info(json_decode(json_encode($response->getData()), true));

        return $response;
    }
}
