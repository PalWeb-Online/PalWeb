@props([
    'name',
    'type' => 'text',
    'errors',
    'error' => $name,
    'value' => false,
    'required' => false,
    'maxlength' => false,
    'autofocus' => false,
    'autocomplete' => 'off',
    'textarea' => false,
    'select' => false
])

<div class="field-item">
    <label for="{{ $name }}">{{ __('field.label.' . $name) }}</label>
    @if($select)
        <select id="{{ $name }}"
                name="{{ $name }}"
                {{ $required ? 'required' : '' }}
                {{ $autofocus ? 'autofocus' : '' }}
                autocomplete="{{ $autocomplete }}"
        >{{ $slot }}</select>
    @else
        <div class="field-input" x-data='{
            inputValue: {{ json_encode((string)$value) }},
            maxlength: {{ json_encode((string)$maxlength) }}
        }'>
            @if($textarea)
                <textarea id="{{ $name }}"
                          name="{{ $name }}"
                          x-on:input="inputValue = $event.target.value"
                          {{ $required ? 'required' : '' }}
                          {{ $autofocus ? 'autofocus' : '' }}
                          autocomplete="{{ $autocomplete }}"
                          x-bind:maxlength="maxlength"
                >{{ $value }}</textarea>
            @else
                <input id="{{ $name }}"
                       type="{{ $type }}"
                       name="{{ $name }}"
                       x-bind:value="inputValue"
                       x-on:input="inputValue = $event.target.value"
                       {{ $required ? 'required' : '' }}
                       {{ $autofocus ? 'autofocus' : '' }}
                       autocomplete="{{ $autocomplete }}"
                       x-bind:maxlength="maxlength"/>
            @endif

            @if($maxlength)
                <div class="field-chars" x-text="`${inputValue.length}/${maxlength}`"></div>
            @endif
        </div>
    @endif

    @if($errors->any())
        <div class="field-info error">
            @error($error)
            {{ $message }}
            @enderror
        </div>
    @else
        <div class="field-info">
            {{ __('field.description.' . $name) }}
        </div>
    @endif
</div>
