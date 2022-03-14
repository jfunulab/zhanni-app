<?php

namespace App;

use Domain\PaymentMethods\Models\UserCard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CreditPayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function remittance(): BelongsTo
    {
        return $this->belongsTo(Remittance::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(UserCard::class);
    }

    public function sourceable(): MorphTo
    {
        return $this->morphTo();
    }
}
