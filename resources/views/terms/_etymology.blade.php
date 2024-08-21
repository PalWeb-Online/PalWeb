@php
    $sortedPatterns = $term->patterns->sortByDesc('type');

    $hasSingular = count($term->patterns->where('type', 'singular')) > 0;
    $hasPlural = count($term->patterns->where('type', 'plural')) > 0;
@endphp

<div>
    {{ ucfirst(__($term->etymology['type'])) }}<span
        style="font-style: italic">{{ $term->etymology['source'] ? ' '.__($term->etymology['source']) : '' }}</span>.

    @foreach ($sortedPatterns as $pattern)
        @if ($pattern->type === 'singular')
            @php
                $patt = $pattern->pattern;

            switch ($pattern->pattern) {
                case 'ia':
                    $patt = 'Intensive Adjective';
                    break;
                case 'na':
                    $patt = 'Nominalized Adjective';
                    break;
                case 'ap':
                    $patt = 'Active Participle';
                    break;
                case 'pp':
                    $patt = 'Passive Participle';
                    break;
                case 'nv':
                    $patt = 'Verbal Noun';
                    break;
                case 'relative':
                    $patt = 'Relative Adjective';
                    break;
            }
            @endphp

            @if ($pattern->form)
                In the <b>Form {{ $pattern->form }} {{ $patt }}</b>
                pattern{{ $hasPlural ? ',' : '.' }}
            @else
                In the <a href="{{ route('wiki.show', 'patterns') }}" target="_blank">{{ $patt }}</a>
                pattern{{ $hasPlural ? ',' : '.' }}
            @endif
        @endif
        @if ($pattern->type === 'plural')
            {{ $hasSingular ? 'with' : 'Has'}} a
            <b>{{ $pattern->pattern }}</b> {{ in_array($pattern->pattern, ['-īn', '-āt']) ? 'sound' : 'broken' }}
            plural.
        @endif
    @endforeach

    @if(count($term->components) > 0)
        Idiom from
        @foreach($term->components as $component)
            <a href="{{ route('terms.show', $component) }}">{{ $component->term }}
                ({{ $component->translit }})</a>{{ !$loop->last ? ',' : '.' }}
        @endforeach
    @endif

    @if(count($term->descendants) > 0)
        Component of
        @foreach($term->descendants as $descendant)
            <a href="{{ route('terms.show', $descendant) }}">{{ $descendant->term }}
                ({{ $descendant->translit }})</a>{{ !$loop->last ? ',' : '.' }}
        @endforeach
    @endif
</div>
