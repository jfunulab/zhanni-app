<?php

namespace Tests\Feature;

use DateTime;
use Tests\TestCase;
use Silamoney\Client\Api\SilaApi;
use Silamoney\Client\Domain\{AchType,
    BalanceEnvironments,
    Country,
    Environments,
    IdentityAlias,
    PlaidTokenType,
    SearchFilters,
    UserBuilder};

class SampleSilaTest extends TestCase
{
    public string $appHandle;
    public string $privateKey;
    public string $userHandle = 'custom_zhanni_user2';
    public string $userPrivateKey = '0xe819ebeb23205ce9853a4cdecb5f4f875eb61379ee4f58ce4053b399f0196f5b';

    protected function setUp(): void
    {
        $this->markTestSkipped('A playground test class');

        parent::setUp();
        $this->appHandle = config('services.sila.app_handle');
        $this->privateKey = config('services.sila.private_key');
    }

    /** @test */
    function testing_out_sila()
    {

        /*$appHandle = config('services.sila.app_handle');
        $privateKey = config('services.sila.private_key'); // Private key used to authenticate app in demo app
        $userHandle = 'zhanni_user1';
        $userPrivateKey = '4a657155ac3dfafe6b76f5aaba20f1dafc54953720516ab9991a9cec5a696252'; // Private key used to authenticate app and create user in demo app
        $plaidToken = 'processor-sandbox-5d17ef59-c90d-40b0-94f6-b3544caf1d33';
        $accountName = null;
        $accountId = null;
        $plaidTokenType = PlaidTokenType::PROCESSOR();

        $client = SilaApi::fromEnvironment(Environments::SANDBOX(), BalanceEnvironments::SANDBOX(), $appHandle, $privateKey);
        $response = $client->linkAccount($userHandle, $userPrivateKey, $plaidToken, $accountName, $accountId, $plaidTokenType);
        dump($response->getData());*/
    }

    /** @test */
    function creating_sila_user()
    {
//        $client = SilaApi::fromEnvironment(Environments::SANDBOX(), BalanceEnvironments::SANDBOX(), $this->appHandle, $this->privateKey);
//        $wallet = $client->generateWallet();
//        dd($wallet);

        // Creating a user
       /* $builder = new UserBuilder();
        $firstName = 'custom_zhanni';
        $lastName = 'user2';
        $email = 'zhanni_user2@zhanni.com';
        $phone = '9876543210';
        $cryptoAddress = '0x91ae363ad201b2c35f9b691aa2eeb4dbeda06a37';
        $birthDate = DateTime::createFromFormat('m/d/Y', '1/8/1985');
        $user = $builder->handle($this->userHandle)
            ->firstName($firstName)
            ->lastName($lastName)
            ->email($email)
            ->phone($phone)
            ->identityNumber('543212222')
            ->address('123 Main St')
            ->city('Anytown')
            ->state('NY')
            ->zipCode('12345')
            ->cryptoAddress($cryptoAddress)
            ->birthDate($birthDate)
            ->build();

        // Call the api
        $response = $client->register($user);
        dump($response);
        */
        // Adding address to user
        /*$nickname = 'zhanni_user2_address';
        $streetAddress1 = '123 Main St'; // This is line 1 of a street address. Post office boxes are not accepted in this field.
        $streetAddress2 = ''; // This is line 2 of a street address (optional). This may include suite or apartment numbers.
        $city = 'Anytown'; // Name of the city where the person being verified is a current resident.
        $state = 'NY'; // Name of state where verified person is a current resident.
        $country = Country::US(); // Two-letter country code.
        $postalCode = '12345'; // In the US, this can be the 5-digit ZIP code or ZIP+4 code.
        $response = $client->addAddress($this->userHandle, $this->userPrivateKey, $nickname, $streetAddress1, $city, $state, $country, $postalCode, $streetAddress2);
        dump($response);
        */

        // Adding Email to user
        /*
        $email = 'your.new.email@domain.com';
        $response = $client->addEmail($this->userHandle, $this->userPrivateKey, $email);
        dump($response);
        */

        // Adding Email to user
        /*
        $phone = '1234567890';
        $response = $client->addPhone($this->userHandle, $this->userPrivateKey, $phone);
        dump($response);
        */

        /*
        $identityAlias = IdentityAlias::SSN();
        $identityValue = '543212222';
        $response = $client->addIdentity($this->userHandle, $this->userPrivateKey, $identityAlias, $identityValue);
        dump($response);
        */

        // KYC
        /*$kycLevel = 'DEFAULT';
        $response = $client->requestKYC($this->userHandle, $this->userPrivateKey, $kycLevel);
        dump($response);
        */

        //Check KYC
//        $response = $client->checkKYC($this->userHandle, $this->userPrivateKey);
//        dump($response);
    }

    /** @test */
    function linking_with_plaid_token()
    {
        // Plaid token flow
        $accountName = null; // Defaults to 'default'
        $accountId = 'mnNb6JNJdqH1kyz9KW3MU1gw6mZoRDTLkVvep'; // Recommended but not required. See note above.
        $plaidToken = 'processor-sandbox-23bc4f2a-95cd-42c4-9f61-8a8b1d981819'; // A temporary token returned from the Plaid Link plugin. See above for testing.
        $plaidTokenType = PlaidTokenType::PROCESSOR(); // Optional. Currently supported values are LEGACY (default), LINK and PROCESSOR

        $client = SilaApi::fromEnvironment(Environments::SANDBOX(), BalanceEnvironments::SANDBOX(), $this->appHandle, $this->privateKey);

        // Call the api
        $response = $client->linkAccount($this->userHandle, $this->userPrivateKey, $plaidToken, $accountName, $accountId, $plaidTokenType);
        dump($response);
    }

    /** @test */
    function get_linked_account_data()
    {
        // Plaid token flow
        $client = SilaApi::fromEnvironment(Environments::SANDBOX(), BalanceEnvironments::SANDBOX(), $this->appHandle, $this->privateKey);

        $response = $client->getAccounts($this->userHandle, $this->userPrivateKey);
        dump($response);
    }

    /** @test */
    function get_transaction_details()
    {
        // Plaid token flow
        $client = SilaApi::fromEnvironment(Environments::SANDBOX(), BalanceEnvironments::SANDBOX(), $this->appHandle, $this->privateKey);

        $filters = new SearchFilters();

        // Call the api
        $response = $client->getTransactions($this->userHandle, $filters, $this->userPrivateKey);
        dump($response);
    }

    /** @test */
    function issue_debit_ach_transfer_on_sila()
    {
        // Plaid token flow
        $amount = 100;
        $accountName = 'default';
        $descriptor = 'Test sila issue'; // Optional
        $businessUuid = 'a9f38290-ce34-42db-95ab-630ebba6084a'; // Optional
        $processingType = AchType::SAME_DAY(); // Optional. Currently supported values are STANDARD (default if not set) and SAME_DAY

        $client = SilaApi::fromEnvironment(Environments::SANDBOX(), BalanceEnvironments::SANDBOX(), $this->appHandle, $this->privateKey);

        // Call the api
        $response = $client->issueSila($this->userHandle, $amount, $accountName, $this->userPrivateKey, $descriptor, $businessUuid, $processingType);
        dump($response->getData());
        // transactionId: d6d0bc99-fd50-4b02-9881-1901c98b8bd4
        // reference: a060045f-1cdb-494f-b392-59d42b61f3c8
        // descriptor: Test sila issue
    }
}
