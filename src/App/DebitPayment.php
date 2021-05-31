<?php

namespace App;

use Domain\PaymentMethods\Models\TransferRecipient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DebitPayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function remittance(): BelongsTo
    {
        return $this->belongsTo(Remittance::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(TransferRecipient::class);
    }
}
