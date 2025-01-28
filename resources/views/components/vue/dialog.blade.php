@props([
    'dialog' => null,
])

@if($dialog)
    <div data-vue-component="DialogContainer" data-props="{{ json_encode(['id' => $dialog->id]) }}"></div>
@endif
