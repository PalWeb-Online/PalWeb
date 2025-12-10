<?php

namespace App\Models;

use App\Models\Scopes\ScoreScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[ScopedBy([ScoreScope::class])]
class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scorable_type',
        'scorable_id',
        'settings',
        'score',
        'results',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'json',
            'results' => 'json',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scorable(): MorphTo
    {
        return $this->morphTo();
    }
}
