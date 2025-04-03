@props([
    'component' => $component,
    'term' => null,
    'gloss' => null,
    'size' => 'm',
])

@if($term)
    <div data-vue-component="{{ $component }}"
         data-props="{{ json_encode(['id' => $term->id, 'glossId' => $gloss?->id, 'size' => $size]) }}"
         class="{{ $component === 'TermHead' ? 'term-head' : '' }}"
    >
    </div>
@endif
