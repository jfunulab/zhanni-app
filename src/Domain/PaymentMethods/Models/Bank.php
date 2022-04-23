<?php

namespace Domain\PaymentMethods\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['code', 'type', 'pay_with_bank'];
    protected $casts = [
        'cash_pickup' => 'boolean'
    ];

    public function scopeAllowsCashPickup($query)
    {
        return $query->where('cash_pickup', true);
    }
}
