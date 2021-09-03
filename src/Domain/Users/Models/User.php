<?php

namespace Domain\Users\Models;

use App\Notifications\EmailVerificationNotification;
use App\Notifications\SendPasswordResetCodeNotification;
use App\Remittance;
use Domain\Bids\Models\Bid;
use Domain\Bids\Models\BidOrder;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\PaymentMethods\Models\BankAccount;
use Domain\PaymentMethods\Models\UserCard;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Billable, \Illuminate\Auth\MustVerifyEmail;

    protected $guarded = [];

    protected $appends = ['verified'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'stripe_id',
        'sila_username',
        'sila_token',
        'sila_address'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'kyc_status' => 'boolean'
    ];

    public function getVerifiedAttribute(): bool
    {
        return !is_null($this->email_verified_at);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name." ".$this->last_name;
    }

    public function cards(): HasMany
    {
        return $this->hasMany(UserCard::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(UserAddress::class);
    }

    public function remittances(): HasMany
    {
        return $this->hasMany(Remittance::class);
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(TransferRecipient::class);
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function bidBuyOrders(): HasMany
    {
        return $this->hasMany(BidOrder::class);
    }

    public function bidSellOrders(): HasMany
    {
        return $this->hasMany(BidOrder::class, 'seller_id');
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(BankAccount::class);
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(app(EmailVerificationNotification::class));
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(app()->make(SendPasswordResetCodeNotification::class, ['token' => $token]));
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return config('services.slack.dump');
    }

    /**
     * Update customer's default payment method.
     *
     * @param  \Stripe\PaymentMethod|string  $paymentMethod
     * @return \Laravel\Cashier\PaymentMethod
     */
    public function updateDefaultPaymentMethod($paymentMethod)
    {
        $this->assertCustomerExists();

        $customer = $this->asStripeCustomer();

        $stripePaymentMethod = $this->resolveStripePaymentMethod($paymentMethod);

        // If the customer already has the payment method as their default, we can bail out
        // of the call now. We don't need to keep adding the same payment method to this
        // model's account every single time we go through this specific process call.
        if ($stripePaymentMethod->id === $customer->invoice_settings->default_payment_method) {
            return;
        }

        $paymentMethod = $this->addPaymentMethod($stripePaymentMethod);

        $customer->invoice_settings = ['default_payment_method' => $paymentMethod->id];

        $customer->save($this->stripeOptions());

        return $paymentMethod;
    }
}
