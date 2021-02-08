<?php

namespace App\Api\Users\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /*
|--------------------------------------------------------------------------
| Email Verification Controller
|--------------------------------------------------------------------------
|
| This controller is responsible for handling email verification for any
| user that recently registered with the application. Emails may also
| be re-sent if the user didn't receive the original email message.
|
*/


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param Request $request
     * @return JsonResponse
     *
     */
    public function verify(Request $request): JsonResponse
    {
        if (! hash_equals((string) $request->route('user')->id, (string) $request->user()->getKey())) {
            return response()->json(['message' => 'Invalid verification code'], 422);
        }

        if ($request->route('code') != $request->user()->email_verification_code) {
            return response()->json(['message' => 'Invalid verification code'], 422);
        }

        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Account already verified'], 422);
        }

        if ($request->route('code') == $request->user()->email_verification_code && $request->user()->verification_code_expires_at < now()) {
            return response()->json(['message' => 'Verification code expired'], 422);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json([
            'message' => 'Account verified',
            'data' => $request->user()->fresh()
        ]);
    }

    /**
     * Resend the email verification notification.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resend(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Account already verified'], 422);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification code sent']);
    }
}
