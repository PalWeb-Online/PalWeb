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

    protected static function booted(): void
    {
        static::creating(function (Gloss $gloss) {
            if ($gloss->position) {
                return;
            }

            $gloss->position = static::query()
                ->where('term_id', $gloss->term_id)
                ->max('position') + 1;
        });
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class);
    }
}
