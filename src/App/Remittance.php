<?php

namespace App;

use Domain\PaymentMethods\Models\BankAccount;
use Domain\PaymentMethods\Models\TransferRecipient;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Remittance extends Model
{
    use HasFactory;

    const COMPLETED = 'completed';
    const IN_PROGRESS = 'in progress';
    const PENDING = 'pending';
    const BANK_DETAILS = 1;
    const CASH_PICKUP = 2;

    const TYPE_MAPPING = [
        self::BANK_DETAILS => 'bank_details',
        self::CASH_PICKUP => 'cash_pickup'
    ];

    protected $guarded = [];
    protected $appends = ['status'];


    public function getStatusAttribute(): string
    {
        if($this->debitPayment && $this->debitPayment->status == 'completed') {
            return self::COMPLETED;
        }

        if(!$this->debitPayment && $this->creditPayment && $this->creditPayment->status == 'success'){
            return self::IN_PROGRESS;
        }

        return self::PENDING;
    }

    public function getTypeAttribute($value): string
    {
        return self::TYPE_MAPPING[$value];
    }

    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = array_search($value,self::TYPE_MAPPING);
    }

    public function isCashPickup(): bool
    {
        return $this->type == self::TYPE_MAPPING[self::CASH_PICKUP];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creditPayment(): HasOne
    {
        return $this->hasOne(CreditPayment::class);
    }

    public function debitPayment(): HasOne
    {
        return $this->hasOne(DebitPayment::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(TransferRecipient::class);
    }

    public function fundingAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function exchangeRate(): BelongsTo
    {
        return $this->belongsTo(ExchangeRate::class );
    }
}
