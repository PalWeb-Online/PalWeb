<?php

namespace App\Models;

use App\Models\Scopes\PublishedScope;
use App\Models\Scopes\UnitScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ScopedBy([UnitScope::class, PublishedScope::class])]
class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'position',
        'title',
        'published',
    ];

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('unit_position');
    }
}
