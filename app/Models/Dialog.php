<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use App\Models\Scopes\DialogScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ScopedBy([DialogScope::class])]
class Dialog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'media',
    ];

    public function sentences(): HasMany
    {
        return $this->hasMany(Sentence::class)->orderBy('position');
    }
}
