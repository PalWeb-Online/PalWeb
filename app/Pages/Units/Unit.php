<?php

namespace App\Pages\Units;

use App\Models\Term;

class Unit
{
    protected string $view;

    protected array $terms = [];

    protected array $lessons = [];

    public function getLesson($lesson)
    {
        $lesson = new $this->lessons[$lesson]();

        return $lesson->render();
    }

    public function render()
    {
        $data = $this->loadTerms();

        return view($this->view, ['terms' => $data]);
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
}
