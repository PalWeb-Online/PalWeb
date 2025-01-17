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

    public function synonyms()
    {
        return $this->relatives()->wherePivot('type', 'synonym');
    }

    public function relatives(): BelongsToMany
    {
        return $this->belongsToMany(Term::class, 'gloss_relative', 'gloss_id', 'relative_id')
            ->withPivot('type');
    }

    public function antonyms()
    {
        return $this->relatives()->wherePivot('type', 'antonym');
    }

    public function valences()
    {
        return $this->relatives()->wherePivotIn('type', ['isPatient', 'noPatient', 'hasObject']);
    }

    public function scopeFilter($query, array $filters): void
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->whereRaw('MATCH(gloss) AGAINST(? IN NATURAL LANGUAGE MODE)', [$search])
                ->orWhere('gloss', 'like', $search.'%');
        });

        $query->when($filters['category'] ?? false, fn ($query, $category) => $query
            ->whereHas('term', fn ($query) => $query->where('category', $category))
        );

        $query->when($filters['attribute'] ?? false, fn ($query, $attribute) => $query
            ->whereHas('term.attributes', fn ($query) => $query->where('attribute', $attribute))
        );

        $query->when($filters['form'] ?? false, fn ($query, $form) => $query
            ->whereHas('term.patterns', fn ($query) => $query->where('form', $form))
        );

        $query->when($filters['singular'] ?? false, fn ($query, $singular) => $query
            ->whereHas('term.patterns', fn ($query) => $query->where('pattern', $singular)->where('type', 'singular'))
        );

        $query->when($filters['plural'] ?? false, fn ($query, $plural) => $query
            ->whereHas('term.patterns', fn ($query) => $query->where('pattern', $plural)->where('type', 'plural'))
        );
    }
}
