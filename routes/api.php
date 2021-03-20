<?php

use App\Api\Users\Controllers\ForgotPasswordController;
use App\Api\Users\Controllers\ResetPasswordController;
use App\Api\Users\Controllers\UserCardsController;
use App\Api\Users\Controllers\UserLoginController;
use App\Api\Users\Controllers\UsersController;
use App\Api\Users\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/users', [UsersController::class, 'store']);
Route::post('/login', [UserLoginController::class, 'login']);
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
Route::post('/email/verify/{user}/{code}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');


Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::put('/users/{user}', [UsersController::class, 'update']);
    Route::post('/users/{user}/cards', [UserCardsController::class, 'store']);
});
