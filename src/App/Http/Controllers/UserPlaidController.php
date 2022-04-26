<?php

namespace App\Http\Controllers;

use Domain\PaymentMethods\Actions\GeneratePlaidLinkTokenAction;
use Domain\Users\Models\User;
use Illuminate\Http\JsonResponse;

class UserPlaidController extends Controller
{
    public function generate(User $user, GeneratePlaidLinkTokenAction $generatePlaidLinkTokenAction): JsonResponse
    {
        $plaidLinkToken = $generatePlaidLinkTokenAction($user);

        return response()->json([
            'message' => 'Plaid link token',
            'data' => [
                'token' => $plaidLinkToken
            ]
        ]);
    }
}
