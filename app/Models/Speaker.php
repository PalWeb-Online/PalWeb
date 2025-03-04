<?php

namespace App\Models;

use App\Models\Scopes\SpeakerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speaker extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static $fluencyLevels = [
        1 => 'Beginner',
        2 => 'Intermediate',
        3 => 'Advanced',
        4 => 'Fluent',
        5 => 'Native',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new SpeakerScope);
    }

    public function getFluencyAliasAttribute(): string
    {
        return self::$fluencyLevels[$this->fluency] ?? 'Unknown';
    }

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dialect(): belongsTo
    {
        return $this->belongsTo(Dialect::class);
    }

    public function location(): belongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function audios(): hasMany
    {
        return $this->hasMany(Audio::class);
    }
}
