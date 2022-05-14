<?php


namespace Domain\Users\Actions;


use App\Exceptions\SilaException;
use Domain\Users\Models\User;
use Support\PaymentGateway\SilaClient;

class UploadSilaKycDocsAction
{
    private SilaClient $silaClient;

    /**
     */
    public function __construct(SilaClient $silaClient)
    {
        $this->silaClient = $silaClient;
    }

    /**
     * @throws SilaException
     */
    public function __invoke(User $user, $file, $documentType, $side): ?User
    {
        $response = $this->silaClient->client->uploadDocument(
            $user->sila_username,
            $user->sila_key,
            $file->path(),
            $file->getFilename(),
            $file->getClientMimeType(),
            $documentType->document_type_name,
            'SSN',
            $side." of ".$documentType->document_type_name
        );
        $responseData = json_decode(json_encode($response->getData()), true);

        if($response->getStatusCode() != 200) {
            throw new SilaException('');
        }

        if($response->getStatusCode() == 200){
            $field = "${side}_id";
            $user->uploadedDocuments()->sync([
                $documentType->id => [$field => $responseData['document_id']]
            ]);

            return $user->fresh(['address']);
        }
    }
}
