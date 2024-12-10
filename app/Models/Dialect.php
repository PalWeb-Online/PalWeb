<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dialect extends Model
{
    protected $fillable = [];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function pronunciations(): HasMany
    {
        return $this->hasMany(Pronunciation::class);
    }

    public function ancestors(): BelongsToMany
    {
        return $this->belongsToMany(Dialect::class, 'dialect_hierarchy', 'descendant_id', 'ancestor_id');
    }

    public function descendants(): BelongsToMany
    {
        return $this->belongsToMany(Dialect::class, 'dialect_hierarchy', 'ancestor_id', 'descendant_id');
    }

    public function speakers(): hasMany
    {
        return $this->hasMany(Speaker::class);
    }
}
