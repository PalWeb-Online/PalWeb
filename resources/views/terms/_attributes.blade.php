@php
    $priority = [
        'collective' => 1,
        'demonym' => 1,
        'defect' => 1,
        'pseudo' => 1,
        'masculine' => 2,
        'feminine' => 2,
        'plural' => 2,
        'idiom' => 3,
        'clitic' => 3,
    ];

    usort($attributes, function($a, $b) use ($priority) {
        return $priority[$a] - $priority[$b];
    });
@endphp

@foreach($attributes as $attribute)
    @switch($attribute)
        @case('collective')
            <a href="{{ route('wiki.show', 'nouns') }}" target="_blank">{{ $attribute }}.</a>
            @break
        @case('demonym')
            <a href="{{ route('wiki.show', 'adjectives') }}" target="_blank">{{ $attribute }}.</a>
            @break
        @case('defect')
            <a href="{{ route('wiki.show', 'adjectives') }}" target="_blank">{{ $attribute }}.</a>
            @break
        @case('pseudo')
            <a href="{{ route('wiki.show', 'verbs') }}" target="_blank">{{ $attribute }}.</a>
            @break
        @case('idiom')
            <span style="font-weight: 400; font-style: italic">{{ $attribute }}.</span>
            @break
        @case('clitic')
            <span style="font-weight: 400; font-style: italic">{{ $attribute }}.</span>
            @break
        @default
            <span style="font-weight: 400">{{ $attribute }}.</span>
    @endswitch
@endforeach
