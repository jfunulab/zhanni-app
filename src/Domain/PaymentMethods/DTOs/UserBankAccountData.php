<?php


namespace Domain\PaymentMethods\DTOs;


use Spatie\DataTransferObject\DataTransferObject;

class UserBankAccountData extends DataTransferObject
{

    public ?string $accountId;
    public ?string $accountName;
    public ?string $institutionName;
    public ?string $institutionId;
    public ?string $plaidPublicToken;

    public static function fromArray(array $request): self
    {
        return new self([
            'accountId' => $request['account_id'] ?? null,
            'accountName' => $request['account_name'] ?? null,
            'institutionName' => $request['institution_name'] ?? null,
            'institutionId' => $request['institution_id'] ?? null,
            'plaidPublicToken' => $request['plaid_public_token'] ?? null,
        ]);
    }
}
