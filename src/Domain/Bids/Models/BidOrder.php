<?php

namespace Domain\Bids\Models;

use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BidOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getAmountAttribute($value)
    {
        return $value / 100;
    }

    public function getMaximumAmountAttribute($value)
    {
        return $value / 100;
    }

    public function getMinimumAmountAttribute($value)
    {
        return $value / 100;
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
