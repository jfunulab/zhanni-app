<?php

namespace App\Api\Users\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login()
    {
        try {
            $this->validate(request(), [
                'email' => ['required'],
                'password' => ['required']
            ]);

            if (!auth()->attempt(request(['email', 'password']))) {
                return response()->json([
                    'message' => 'Invalid email or password'
                ], 401);
            }
            $user = auth()->user();

            if(!Str::startsWith(request()->path(), 'api')){
                return redirect()->intended();
            }

            return response()->json([
                'message' => 'Login successful',
                'data' => [
                    'token' => $user->createToken('access_token', ['*'])->plainTextToken,
                    'token_type' => 'bearer',
                    'user' => $user
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Invalid email or password',
                'errors' => $e->errors()
            ], 422);
        }
    }

}
