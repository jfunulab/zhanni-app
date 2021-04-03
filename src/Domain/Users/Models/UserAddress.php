<?php

namespace Domain\Users\Models;

use Domain\Countries\Models\Country;
use Domain\Countries\Models\CountryState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(CountryState::class);
    }
}
