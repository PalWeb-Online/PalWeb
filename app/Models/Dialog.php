<?php

namespace App\Models;

use App\Models\Scopes\DialogScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dialog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'media',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new DialogScope);
    }

    public function sentences(): HasMany
    {
        return $this->hasMany(Sentence::class)->orderBy('position');
    }
}
