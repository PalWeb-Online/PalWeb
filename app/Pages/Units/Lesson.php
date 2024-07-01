<?php

namespace App\Pages\Units;

use App\Models\Sentence;
use App\Models\Term;

class Lesson
{
    protected string $view;

    protected array $terms = [];

    protected array $sentences = [];

    public function render()
    {
        $terms = $this->loadTerms();
        $sentences = $this->loadSentences();

        return view($this->view, [
            'terms' => $terms,
            'sentences' => $sentences,
        ]);
    }

    public function loadTerms()
    {
        $query = Term::query()->with(['pronunciations', 'glosses']);

        foreach ($this->terms as $term) {
            $query->orWhere('slug', $term);
        }

        $terms = $query->get()->keyBy('slug');

        $keys = array_fill_keys($this->terms, null);

        return collect($keys)->merge($terms);
    }

    public function loadSentences()
    {
        $query = Sentence::query();

        foreach ($this->sentences as $sentence) {
            $query->orWhere('translit', $sentence);
        }

        $sentences = $query->get();

        return $sentences->mapWithKeys(function ($sentence) {
            return [$sentence['translit'] => $sentence];
        });
    }
}
