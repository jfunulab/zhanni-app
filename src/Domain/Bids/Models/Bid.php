<?php

namespace Domain\Bids\Models;

use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bid extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getMaximumAmountAttribute($value)
    {
        return $value / 100;
    }

    public function getMinimumAmountAttribute($value)
    {
        return $value / 100;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
