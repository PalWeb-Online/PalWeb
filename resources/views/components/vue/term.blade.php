@props([
    'component' => $component,
    'term' => $term,
    'gloss' => false,
    'size' => 'm',
])

@if($term)
    @php
        $term->pronunciation = $term->pronunciations->first();

        if(auth()->check()) {
            $pronunciation = $term->pronunciations->firstWhere('dialect_id', auth()->user()->dialect_id);
            $pronunciation && $term->pronunciation = $pronunciation;

            $userDecks = auth()->user()->decks->map(function ($deck) use ($term) {
                return [
                    'id' => $deck->id,
                    'name' => $deck->name,
                    'isPresent' => $deck->terms->contains($term->id)
                ];
            })->toArray();
        } else {
            $userDecks = [];
        }

        $termObject = [
            'term' => $term->term,
            'translit' => $term->pronunciation->translit,
            'file' => $term->pronunciation->audify(),
            'gloss' => $gloss->gloss ?? $term->glosses[0]->gloss,
            'size' => $size,
        ];
    @endphp

    <div data-vue-component="{{ $component }}"
         data-props="{{ json_encode([
                 'modelType' => 'term',
                 'term' => $termObject,
                 'imageURL' => asset('/img'),
                 'isPinned' => $term->isPinned(),

                 'routes' => [
                     'view' => route('terms.show', $term),
                     'usages' => route('terms.usages', $term),
                     'edit' => route('terms.edit', $term),
                     'delete' => route('terms.destroy', $term),
                     'pin' => route('terms.pin', $term),
                     'deckToggle' => route('decks.term.toggle', ['deck' => ':deckId', 'term' => $term->id])
                 ],
                 'isUser' => auth()->check(),
                 'isAdmin' => auth()->check() && auth()->user()->isAdmin(),

                 'userDecks' => $userDecks,
             ]) }}"
         class="{{ $component === 'TermHead' ? 'term-head' : '' }}"
    >
    </div>
@endif
