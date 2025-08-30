<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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

    protected $casts = [
        'settings' => 'json',
        'results' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scorable(): MorphTo
    {
        return $this->morphTo();
    }
}
