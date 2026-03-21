<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardReview extends Model
{
    protected $fillable = [
        'user_id',
        'card_id',
        'type',
        'grade',
        'seconds_spent',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
