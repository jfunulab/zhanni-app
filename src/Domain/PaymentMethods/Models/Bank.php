<?php

namespace Domain\PaymentMethods\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['code', 'type', 'pay_with_bank'];
}
