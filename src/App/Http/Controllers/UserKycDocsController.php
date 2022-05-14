<?php

namespace App\Http\Controllers;

use App\Exceptions\SilaException;
use App\Http\Requests\UploadKycDocsRequest;
use App\VerificationDocument;
use Domain\Users\Actions\UploadSilaKycDocsAction;
use Domain\Users\Models\User;
use Illuminate\Http\JsonResponse;

class UserKycDocsController extends Controller
{

    public function store(User $user, UploadKycDocsRequest $request, UploadSilaKycDocsAction $uploadSilaKycDocsAction): JsonResponse
    {
        try {
            $file = $request->file('document');
            $side = $request->get('side');
            $documentType = VerificationDocument::find($request->get('document_type_id'));
            $user = ($uploadSilaKycDocsAction)($user, $file, $documentType, $side);

            return response()->json([
                'message' => 'Document uploaded successfully',
                'data' => $user
            ]);
        } catch (SilaException $e) {
            return response()->json([
                'message' => 'Unable to initiate remittance at this time.'
            ], 400);
        }
    }
}
