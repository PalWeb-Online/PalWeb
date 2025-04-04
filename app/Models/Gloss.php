<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Gloss extends Model
{
    use HasFactory;

    protected $guarded = ['sentences'];

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function relatives(): BelongsToMany
    {
        return $this->belongsToMany(Term::class, 'gloss_relative', 'gloss_id', 'relative_id')
            ->withPivot('type');
    }

//    public function synonyms()
//    {
//        return $this->relatives()->wherePivot('type', 'synonym');
//    }
//
//    public function antonyms()
//    {
//        return $this->relatives()->wherePivot('type', 'antonym');
//    }
//
//    public function valences()
//    {
//        return $this->relatives()->wherePivotIn('type', ['isPatient', 'noPatient', 'hasObject']);
//    }
}
