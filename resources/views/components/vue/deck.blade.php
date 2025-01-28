@props([
    'component' => $component,
    'deck' => null,
    'size' => 'm'
])

@if($deck)
    <div data-vue-component="{{ $component }}"
         data-props="{{ json_encode(['id' => $deck->id, 'size' => $size]) }}"
    >
    </div>
@endif
