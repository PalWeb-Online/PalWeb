<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Spelling extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function terms(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }
}
