<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
    protected $fillable = [];

    public function terms(): belongsToMany
    {
        return $this->belongsToMany(Term::class);
    }
}
