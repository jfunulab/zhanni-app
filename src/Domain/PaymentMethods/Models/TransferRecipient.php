<?php

namespace Domain\PaymentMethods\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferRecipient extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}
