@props([
    'component' => $component,
    'dialog' => null,
])

@if($dialog)
    <div data-vue-component="{{ $component }}"
         data-props="{{ json_encode(['id' => $dialog->id]) }}"
    ></div>
@endif
