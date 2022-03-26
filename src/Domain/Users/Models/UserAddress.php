<?php

namespace Domain\Users\Models;

use App\Jobs\RegisterUserSilaAccountJob;
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
            $user = $address->user;
            $kycIssues = $user->kyc_issues;

            if(is_array($kycIssues) && !in_array('kyc_issues', $propertyChanges) && count($kycIssues) > 0){
                foreach ($propertyChanges as $propertyChange) {
                    unset($kycIssues[$propertyChange]);

                    if($propertyChange == 'identity_number'){
                        unset($kycIssues['identity']);
                    }
                }

                $user->update(['kyc_issues' => count($kycIssues) > 0 ? $kycIssues : null]);

                if (count($kycIssues) == 0 && is_null($user->sila_key)) {
                    RegisterUserSilaAccountJob::dispatch($user);
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
