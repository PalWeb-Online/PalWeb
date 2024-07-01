<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pattern extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'form',
        'pattern',
    ];

    protected $guarded = [];

    public function terms()
    {
        return $this->belongsToMany(Term::class);
    }
}
