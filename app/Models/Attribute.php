<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
    protected $fillable = [];

    public function terms(): BelongsToMany
    {
        return $this->belongsToMany(Term::class);
    }

    public function glosses(): BelongsToMany
    {
        return $this->belongsToMany(Gloss::class);
    }
}
