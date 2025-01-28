@props([
    'sentence' => null,
    'currentTerm' => null,
    'size' => 'm',
])

@if($sentence)
    <div data-vue-component="SentenceItem"
         data-props="{{ json_encode(['id' => $sentence->id, 'currentTerm' => $currentTerm, 'size' => $size]) }}"
    >
    </div>
@endif
