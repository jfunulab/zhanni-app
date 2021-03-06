<?php


namespace App\Providers;


use Illuminate\Auth\Passwords\PasswordResetServiceProvider as BasePasswordResetServiceProvider;
use Support\PasswordReset\PasswordBrokerManager;

class PasswordResetServiceProvider extends BasePasswordResetServiceProvider
{

    /**
     * Register the password broker instance.
     *
     * @return void
     */
    protected function registerPasswordBroker()
    {
        $this->app->singleton('auth.password', function ($app) {
            return new PasswordBrokerManager($app);
        });

        $this->app->bind('auth.password.broker', function ($app) {
            return $app->make('auth.password')->broker();
        });
    }
}
