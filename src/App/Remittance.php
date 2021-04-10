<?php

namespace App;

use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Remittance extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['status'];

    public function getStatusAttribute()
    {
        if($this->debitPayment && $this->debitPayment->status == 'paid') {
            return 'completed';
        }
        if(!$this->debitPayment && $this->creditPayment && $this->creditPayment->status == 'paid'){
            return 'in progress';
        }
        return 'pending';
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
}
