<?php


namespace Domain\PaymentMethods\Actions;


use App\CreditPayment;
use App\Exceptions\SilaTransactionCancellationException;
use Silamoney\Client\Api\ApiResponse;
use Support\PaymentGateway\SilaClient;


class CancelSilaTransactionAction
{

    private SilaClient $silaClient;

    public function __construct(SilaClient $silaClient)
    {
        $this->silaClient = $silaClient;
    }


    /**
     * @throws SilaTransactionCancellationException
     */
    public function __invoke(CreditPayment $creditPayment): ApiResponse
    {
        info($creditPayment->toArray());
        $user = $creditPayment->remittance->user;
        $response = $this->silaClient->client->cancelTransaction(
            $user->sila_username,
            $user->sila_key,
            $creditPayment->reference_id
        );

        info(json_decode(json_encode($response->getData()), true));
        if($response->getStatusCode() != 200){
            throw new SilaTransactionCancellationException('Unable to cancel transaction at the moment.');
        }

        return $response;
    }
}
