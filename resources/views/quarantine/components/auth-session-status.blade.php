@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => '']) }}>
        {{ $status }}
    </div>
@endif
