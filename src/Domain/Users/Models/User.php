<?php

namespace Domain\Users\Models;

use App\Jobs\RegisterUserSilaAccountJob;
use App\Jobs\UpdateSilaUserJob;
use App\Notifications\EmailVerificationNotification;
use App\Notifications\SendPasswordResetCodeNotification;
use App\Remittance;
use App\VerificationCategory;
use App\VerificationDocument;
use Domain\Bids\Models\Bid;
use Domain\Bids\Models\BidOrder;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\PaymentMethods\Models\BankAccount;
use Domain\PaymentMethods\Models\UserCard;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    protected $with = ['verificationDocumentsCategoryRequired', 'uploadedDocuments'];

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
        'sila_address',
        'sila_key'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'kyc_status' => 'string',
        'kyc_issues' => 'array',
        'identity_number' => 'encrypted',
        'sila_key' => 'encrypted',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function($user){
            $changes = $user->getDirty();
            $propertyChanges = array_keys($changes);
            $kycIssues = $user->kyc_issues;
//            if(is_array($kycIssues) && !in_array('kyc_issues', $propertyChanges) && count($kycIssues) > 0){
            if($user->kyc_status != 'passed'){
                foreach ($propertyChanges as $propertyChange) {
                    unset($kycIssues[$propertyChange]);

                    if($propertyChange == 'identity_number'){
                        unset($kycIssues['identity']);
                    }
                }

                if(is_array($kycIssues)) {
                    $user->update(['kyc_issues' => (count($kycIssues) > 0 && $kycIssues != $user->kyc_issues) ? $kycIssues : null]);
                    if (count($kycIssues) == 0 && is_null($user->sila_key)) {
                        RegisterUserSilaAccountJob::dispatch($user);
                    }
                }

                if($user->kyc_status == 'failed'
                    && !is_null($user->sila_key)
                    && (
                        in_array('first_name', $propertyChanges)
                        || in_array('last_name', $propertyChanges)
                        || in_array('identity_number', $propertyChanges)
                        || in_array('phone_number', $propertyChanges)
                        || in_array('birth_date', $propertyChanges)
                    )
                ) {
                    UpdateSilaUserJob::dispatch($user, $propertyChanges);
                }
            }
        });
    }

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
        return $this->hasOne(UserAddress::class)->latestOfMany();
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

    public function passedKyc(): bool
    {
        return $this->kyc_status == 'passed';
    }

    public function verificationDocumentsCategoryRequired(): BelongsToMany
    {
        return $this->belongsToMany(
            VerificationCategory::class,
            'user_verification_documents_category_required',
            'user_id',
            'verification_category_id'
        )->withTimestamps();
    }

    public function uploadedDocuments(): BelongsToMany
    {
        return $this->belongsToMany(
            VerificationDocument::class,
            'user_uploaded_verification_documents',
            'user_id',
            'document_type_id'
        )->withPivot(['front_id', 'back_id'])->as('sides');
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
