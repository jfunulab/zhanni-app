<?php


namespace Domain\Users\Actions;


use App\VerificationCategory;
use Domain\Users\Models\User;
use Support\PaymentGateway\SilaClient;

class CheckSilaUserKycAction
{
    private SilaClient $silaClient;
    private $kycIssuesCategoryMap = [
        'Name Not Verified' => VerificationCategory::GENERAL,
        'Address Not Verified' => VerificationCategory::ADDRESS,
        'DOB Not Verified' => VerificationCategory::BIRTH_DATE,
        'SSN Not Verified' => VerificationCategory::SSN
    ];

    /**
     */
    public function __construct(SilaClient $silaClient)
    {
        $this->silaClient = $silaClient;
    }

    /**
     */
    public function __invoke(User $user)
    {
        $response = $this->silaClient->client->checkKYC($user->sila_username, $user->sila_key);

        if ($response->getStatusCode() == 200) {
            $responseData = $response->getData();
            info('kyc information');
            info(json_decode(json_encode($response->getData()), true));

            $kycIssues = end($responseData->verification_history)->reasons;
            $user->update([
                'kyc_status' => $responseData->verification_status,
                'kyc_issues' => $kycIssues
            ]);

            if(count($kycIssues) > 0){
                $user->verificationDocumentsCategoryRequired()
                    ->sync(array_values(array_intersect_key($this->kycIssuesCategoryMap, array_flip($kycIssues))));
            }
        }
    }
}
