<?php

namespace Domain\Countries\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function states(): HasMany
    {
        return $this->hasMany(CountryState::class);
    }
}
