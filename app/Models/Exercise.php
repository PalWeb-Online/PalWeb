<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exercise extends Model
{
    /** @use HasFactory<\Database\Factories\ExerciseFactory> */
    use HasFactory;

    protected $casts = [
        'options' => 'json',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}
