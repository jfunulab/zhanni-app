<?php


namespace Domain\PaymentMethods\Actions;


use App\Exceptions\PlaidException;
use Domain\PaymentMethods\DTOs\UserBankAccountData;
use Domain\Users\Models\User;

class AddBankAccountAction
{
    private RegisterUserSilaAccountAction $registerUserSilaAccountAction;
    private GeneratePlaidAccessTokenAction $generatePlaidAccessTokenAction;
    private GenerateSilaProcessorTokenAction $generateSilaProcessorTokenAction;
    private UserCanConnectPlaidAccountAction $userCanConnectBankAccount;
    private LinkBankAccountToSilaAction $linkBankAccountToSilaAction;

    /**
     * AddBankAccountAction constructor.
     * @param RegisterUserSilaAccountAction $registerUserSilaAccountAction
     */
    public function __construct(GeneratePlaidAccessTokenAction $generatePlaidAccessTokenAction,
                                GenerateSilaProcessorTokenAction $generateSilaProcessorTokenAction,
                                RegisterUserSilaAccountAction $registerUserSilaAccountAction,
                                UserCanConnectPlaidAccountAction $userCanConnectBankAccount,
                                LinkBankAccountToSilaAction $linkBankAccountToSilaAction)
    {
        $this->registerUserSilaAccountAction = $registerUserSilaAccountAction;
        $this->generatePlaidAccessTokenAction = $generatePlaidAccessTokenAction;
        $this->generateSilaProcessorTokenAction = $generateSilaProcessorTokenAction;
        $this->userCanConnectBankAccount = $userCanConnectBankAccount;
        $this->linkBankAccountToSilaAction = $linkBankAccountToSilaAction;
    }

    /**
     * @throws \App\Exceptions\BankConnectionException
     */
    public function __invoke(User $user, UserBankAccountData $data)
    {
        if(($this->userCanConnectBankAccount)($user)){
            try {
                $bankAccount = $user->bankAccounts()->firstOrNew([
                    'account_name' => $data->accountName,
                    'account_id' => $data->accountId,
                    'institution_name' => $data->institutionName,
                    'institution_id' => $data->institutionId,
                ]);

                if(!$bankAccount->plaid_data){
                    $plaidAccessToken = ($this->generatePlaidAccessTokenAction)($data->plaidPublicToken);

                    $bankAccount->fill(['plaid_data' => [
                        'account_name' => $data->accountName,
                        'account_id' => $data->accountId,
                        'institution_name' => $data->institutionName,
                        'institution_id' => $data->institutionId,
                        'access_token' => $plaidAccessToken
                    ]])->save();

                    $silaProcessorToken = ($this->generateSilaProcessorTokenAction)($plaidAccessToken, $data->accountId);
                    $bankAccount->update(['plaid_data->sila_processor_token' => $silaProcessorToken]);
                }

                if(!$user->sila_user_name) {
                    ($this->registerUserSilaAccountAction)($user);
                }

                if ($user->passedKyc()) {
                    $bankAccount = ($this->linkBankAccountToSilaAction)($user, $bankAccount);
                }

                return $bankAccount;
            } catch (PlaidException $e) {
                info($e);
            }
        }
    }
}
