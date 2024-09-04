@if(isset($term))
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
            'gloss' => $gloss->gloss ?? $term->glosses[0]->gloss
        ];
    @endphp

    <div class="term-li-container">
        <div data-vue-component="TermItem"
             data-props="{{ json_encode([
                 'term' => $termObject,
                 'imageURL' => asset('/img'),
                 'isPinned' => $term->isPinned(),

                 'modelType' => 'term',

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
        >
        </div>

        {{ $slot }}
    </div>

@elseif(isset($subterm))
    <div class="term-li subterm">
        <div class="arb">{{ $arb }}</div>
        <div class="eng">{{ $eng }}</div>
    </div>

@elseif(isset($arb))
    <div class="term-li-container">
        <div class="term-li-wrapper">
            <div class="term-li">
                <div class="arb">{{ $arb }}</div>
                <div class="eng">{{ $eng }}</div>
            </div>
        </div>
        {{ $slot }}
    </div>
@else
    <div class="term-li coming-soon">
        <div class="feature-callout">
            {{ __('coming soon') }}
        </div>
    </div>
@endif
