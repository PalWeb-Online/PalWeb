<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Gloss extends Model
{
    use HasFactory;

    protected $guarded = ['sentences'];

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class);
    }
}
