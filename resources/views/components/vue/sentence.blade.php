@props([
    'component' => $component,
    'sentence' => false,
    'currentTerm' => false,
    'size' => 'm',
    'eng' => false,
])

@if($sentence)
    @php
        $sentenceObject = [
            'sentence' => $sentence->sentence,
            'trans' => $sentence->trans,
            'audio' => null,
            'terms' => $sentence->allTerms()->map(function ($term) use ($currentTerm) {
                return [
                    'slug' => $term->slug,
                    'term' => $term->sent_term,
                    'translit' => $term->sent_translit,
                    'isCurrent' => $currentTerm === $term->id
                ];
            })->toArray(),
            'size' => $size,
        ];
    @endphp

    <div data-vue-component="{{ $component }}"
         data-props="{{ json_encode([
                 'sentence' => $sentenceObject,
                 'imageURL' => asset('/img'),
                 'isPinned' => $sentence->isPinned(),

                 'routes' => [
                     'view' => route('sentences.show', $sentence),
                     'edit' => route('sentences.edit', $sentence),
                     'delete' => route('sentences.destroy', $sentence),
                     'pin' => route('sentences.pin', $sentence),
                     'viewTerm' => route('terms.show', ':termId'),
                 ],
                 'isUser' => auth()->check(),
                 'isAdmin' => auth()->check() && auth()->user()->isAdmin(),
             ]) }}"
    >
    </div>
@endif
