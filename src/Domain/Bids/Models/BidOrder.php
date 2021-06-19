<?php

namespace Domain\Bids\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getMaximumAmountAttribute($value)
    {
        return $value/100;
    }

    public function getMinimumAmountAttribute($value)
    {
        return $value/100;
    }
}
