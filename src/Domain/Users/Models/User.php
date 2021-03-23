<?php

namespace Domain\Users\Models;

use App\Notifications\EmailVerificationNotification;
use App\Notifications\SendPasswordResetCodeNotification;
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
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getVerifiedAttribute(): bool
    {
        return !is_null($this->email_verified_at);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(UserCard::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(UserAddress::class);
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
}
