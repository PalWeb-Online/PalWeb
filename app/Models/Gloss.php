<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gloss extends Model
{
    use HasFactory;

    protected $guarded = ['sentences'];
    protected $casts = [
        'relatives' => 'array',
    ];

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function sentences()
    {
        return $this->belongsToMany(Sentence::class);
    }

    public function valences()
    {
        return $this->belongsToMany(Term::class, 'gloss_relative', 'gloss_id', 'relative_id')
            ->wherePivotIn('type', ['isPatient', 'noPatient', 'hasObject'])
            ->withPivot('type');
    }

    public function synonyms()
    {
        return $this->belongsToMany(Term::class, 'gloss_relative', 'gloss_id', 'relative_id')
            ->wherePivot('type', 'synonym');
    }

    public function antonyms()
    {
        return $this->belongsToMany(Term::class, 'gloss_relative', 'gloss_id', 'relative_id')
            ->wherePivot('type', 'antonym');
    }
}
