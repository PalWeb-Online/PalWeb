@php
    if(auth()->check()) {
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
        'translit' => $term->translit,
        'file' => $term->pronunciations[0]->audify(),
        'gloss' => $gloss->gloss ?? $term->glosses[0]->gloss
    ];
@endphp

<div data-vue-component="TermHead"
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
     class="term-head"
>
</div>
