<?php

namespace Domain\Users\Models;

use App\Jobs\UpdateSilaUserJob;
use Domain\Countries\Models\Country;
use Domain\Countries\Models\CountryState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['country', 'state'];

    protected static function boot()
    {
        parent::boot();

        static::updated(function($address){
            $changes = $address->getDirty();
            $propertyChanges = array_keys($changes);

            if($address->user->kyc_status != 'passed'){
                if($address->user->kyc_status == 'failed'
                    && !is_null($address->user->sila_key)
                    && (
                        in_array('country_id', $propertyChanges)
                        || in_array('state_id', $propertyChanges)
                        || in_array('line_one', $propertyChanges)
                        || in_array('line_two', $propertyChanges)
                        || in_array('city', $propertyChanges)
                        || in_array('postal_code', $propertyChanges)
                    )
                ) {
                    info('going to update sila user');
                    UpdateSilaUserJob::dispatch($address->user, $propertyChanges);
                }
            }
        });
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(CountryState::class);
    }
}
