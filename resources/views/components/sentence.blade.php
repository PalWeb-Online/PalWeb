@props([
    'sentence' => false,
    'currentTerm' => false,
    'size' => 'm',
    'eng' => false,
])

@php
    $sentenceObject = [
        'sentence' => $sentence->sentence,
        'trans' => $sentence->trans,
        'file' => $sentence->audify(),
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

{{-- if the Sentence model exists --}}
@if($sentence)
    <div data-vue-component="SentenceItem"
         data-props="{{ json_encode([
                 'sentence' => $sentenceObject,
                 'imageURL' => asset('/img'),
                 'isPinned' => $sentence->isPinned(),

                 'modelType' => 'sentence',

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

    {{-- if the Sentence is built in the view --}}
@elseif($eng)
    <div class="sentence-wrapper" style="justify-self: center">
        <div class="sentence {{ $size }}">
            <div class="sentence-arb">
                {{ $slot }}
            </div>
            <div class="sentence-eng">
                {{ $eng }}
            </div>
        </div>
    </div>

    {{-- if there is no data for the Sentence; i.e. if the Sentence model should be retrieved, but doesn't exist yet --}}
@else
    <div class="sentence coming-soon">
        <div class="feature-callout">
            {{ __('coming soon') }}
        </div>
    </div>
@endif
