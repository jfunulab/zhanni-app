<?php


namespace Domain\PaymentMethods\Actions;


use App\Exceptions\PlaidException;
use TomorrowIdeas\Plaid\Plaid;
use TomorrowIdeas\Plaid\PlaidRequestException;


class GenerateSilaProcessorTokenAction
{

    private Plaid $plaidClient;

    public function __construct(Plaid $plaidClient)
    {
        $this->plaidClient = $plaidClient;
    }

    /**
     * @throws PlaidException
     */
    public function __invoke(string $plaidAccessToken, string $accountId)
    {
        try {
            $response = $this->plaidClient->processors->createToken($plaidAccessToken, $accountId, 'sila_money');

            return $response->processor_token;
        } catch (PlaidRequestException $e) {
            throw new PlaidException($e->getMessage());
        }
    }
}
