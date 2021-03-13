<?php

namespace Domain\Users\Models;

use App\Notifications\EmailVerificationNotification;
use App\Notifications\SendPasswordResetCodeNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, \Illuminate\Auth\MustVerifyEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'address',
        'phone_number',
        'email_verification_code',
        'verification_code_expires_at'
    ];

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
