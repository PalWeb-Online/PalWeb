<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speaker extends Model
{
    use HasFactory;

    protected $guarded = [];

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
