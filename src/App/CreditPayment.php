<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditPayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function remittance(): BelongsTo
    {
        return $this->belongsTo(Remittance::class);
    }
}
