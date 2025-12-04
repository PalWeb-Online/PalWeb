<?php

namespace App\Models;

use App\Models\Scopes\UnitScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'position',
        'title',
        'published',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new UnitScope);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('position');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }
}
