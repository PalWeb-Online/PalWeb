@props([
    'sentence' => null,
    'currentTerm' => null,
    'size' => 'm',
    'speaker' => false,
    'dialog' => false
])

@if($sentence)
    <div data-vue-component="SentenceItem"
         data-props="{{ json_encode([
            'id' => $sentence->id,
            'currentTerm' => $currentTerm,
            'size' => $size,
            'speaker' => $speaker,
            'dialog' => $dialog
        ]) }}"
    >
    </div>
@endif
