<?php


namespace Domain\PaymentMethods\Actions;


use Domain\Users\Models\User;
use TomorrowIdeas\Plaid\Entities\User as PlaidUser;
use TomorrowIdeas\Plaid\Plaid;

class GeneratePlaidLinkTokenAction
{
    private Plaid $plaid;

    /**
     * GeneratePlaidLinkTokenAction constructor.
     * @param Plaid $plaid
     */
    public function __construct(Plaid $plaid)
    {
        $this->plaid = $plaid;
    }

    public function __invoke(User $user): string
    {
        $result = $this->plaid->tokens->create(
            config('services.plaid.app_name'),
            "en", ["US"],
            new PlaidUser($user->id),
            ["assets", "auth", "identity", "transactions"]
        );

        return $result->link_token;
    }
}
