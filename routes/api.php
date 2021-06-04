<?php

use App\Api\Users\Controllers\ForgotPasswordController;
use App\Api\Users\Controllers\ResetPasswordController;
use App\Api\Users\Controllers\UserAddressController;
use App\Api\Users\Controllers\UserCardsController;
use App\Api\Users\Controllers\UserLoginController;
use App\Api\Users\Controllers\UserRecipientsController;
use App\Api\Users\Controllers\UsersController;
use App\Api\Users\Controllers\VerificationController;
use App\Http\Controllers\Banks\BanksController;
use App\Http\Controllers\BidsController;
use App\Http\Controllers\Countries\CountriesController;
use App\Http\Controllers\Countries\CountryStatesController;
use App\Http\Controllers\ExchangeRatesController;
use App\Http\Controllers\UserBidOrdersController;
use App\Http\Controllers\UserBidsController;
use App\Http\Controllers\UserRemittancesController;
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

Route::get('/countries', [CountriesController::class, 'index']);
Route::get('/countries/{country}/states', [CountryStatesController::class, 'index']);
Route::get('/exchange-rates', [ExchangeRatesController::class, 'index']);
Route::post('/users', [UsersController::class, 'store']);
Route::post('/login', [UserLoginController::class, 'login']);
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
Route::post('/email/verify/{user}/{code}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');


Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('/banks', [BanksController::class, 'index']);
    Route::put('/users/{user}', [UsersController::class, 'update']);
    Route::get('/users/{user}/cards', [UserCardsController::class, 'index']);
    Route::post('/users/{user}/cards', [UserCardsController::class, 'store']);
    Route::post('/users/{user}/address', [UserAddressController::class, 'store']);
    Route::get('/users/{user}/recipients', [UserRecipientsController::class, 'index']);
    Route::post('/users/{user}/recipients', [UserRecipientsController::class, 'store']);

    Route::get('/users/{user}/remittances', [UserRemittancesController::class, 'index']);
    Route::post('/users/{user}/remittances', [UserRemittancesController::class, 'store']);

    //Bidding
    Route::get('/bids', [BidsController::class, 'index']);
    Route::post('/users/{user}/bids', [UserBidsController::class, 'store']);
    Route::get('/users/{user}/bids/buy-orders', [UserBidOrdersController::class, 'buyIndex']);
    Route::post('/bids/{bid}/orders', [UserBidOrdersController::class, 'store']);
});
