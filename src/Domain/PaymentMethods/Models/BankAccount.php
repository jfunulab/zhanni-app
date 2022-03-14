<?php


namespace Domain\PaymentMethods\Models;


use App\CreditPayment;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class BankAccount extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'plaid_data' => 'array'
    ];
    protected $hidden = ['plaid_data', 'sila_data'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creditPayments(): MorphMany
    {
        return $this->morphMany(CreditPayment::class, 'sourceable');
    }
}
