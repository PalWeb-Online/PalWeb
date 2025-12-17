<?php

namespace App\Models;

use App\Models\Traits\HasScoreStats;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Activity extends Model
{
    use HasFactory;
    use HasScoreStats;

    protected $fillable = [
        'title',
        'document',
        'published',
    ];

    protected function casts(): array
    {
        return [
            'document' => 'json',
        ];
    }

    public function lesson(): HasOne
    {
        return $this->hasOne(Lesson::class);
    }

    public function scores(): MorphMany
    {
        return $this->morphMany(Score::class, 'scorable')->orderByDesc('created_at');
    }
}
