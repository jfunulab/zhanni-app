<?php


namespace Domain\PaymentMethods\Actions;


use App\Exceptions\PlaidException;
use TomorrowIdeas\Plaid\Plaid;
use TomorrowIdeas\Plaid\PlaidRequestException;


class GeneratePlaidAccessTokenAction
{

    private Plaid $plaidClient;

    public function __construct(Plaid $plaidClient)
    {
        $this->plaidClient = $plaidClient;
    }

    /**
     * @throws PlaidException
     */
    public function __invoke(string $plaidPublicToken)
    {
        try {
            $accessToken = $this->plaidClient->items->exchangeToken($plaidPublicToken);
            return $accessToken->access_token;
        } catch (PlaidRequestException $e) {
            throw new PlaidException($e->getMessage());
        }
    }
}
