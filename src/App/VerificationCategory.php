<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VerificationCategory extends Model
{
    use HasFactory;
    const SSN = 1;
    const ADDRESS = 2;
    const GENERAL = 3;
    const BIRTH_DATE = 4;

    protected $with = ['documents'];

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(VerificationDocument::class,
            'verification_category_documents',
            'verification_category_id',
            'verification_category_document_id'
        )->withTimestamps();
    }
}
