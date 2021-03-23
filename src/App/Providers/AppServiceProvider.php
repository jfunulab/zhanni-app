<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Support\PaymentGateway\LocalPaymentGateway;
use Support\PaymentGateway\MakesBankTransfer;
use Support\PaymentGateway\Paystack\PaystackGateway;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Sanctum::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(LocalPaymentGateway::class, PaystackGateway::class);
        $this->app->bind(MakesBankTransfer::class, PaystackGateway::class);
    }
}
