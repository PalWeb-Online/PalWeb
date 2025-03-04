<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pattern extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'form',
        'pattern',
    ];

    protected $guarded = [];

    protected static $namedPatterns = [
        'ap' => 'Active Participle',
        'ia' => 'Intensive Adjective',
        'pp' => 'Passive Participle',
        'nv' => 'Verbal Noun',
        'na' => 'Nominalized Adjective',
        'relative' => 'Relative Adjective',
    ];

    public function getPatternAliasAttribute(): string
    {
        return self::$namedPatterns[$this->pattern] ?? $this->pattern;
    }

    public function terms(): BelongsToMany
    {
        return $this->belongsToMany(Term::class);
    }
}
