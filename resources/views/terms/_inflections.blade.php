@if(count($term->inflections->where('form', 'host')) > 0)
    @if($term->slug === 'preposition-b-')
        <x-conj.b/>
    @else
        @foreach ($term->inflections->where('form', 'host') as $hostForm)
            <x-conj.inflection host="{{ $hostForm->inflection }}" translit="{{ $hostForm->translit }}"/>
        @endforeach
    @endif

@elseif($term->category == 'verb')
    @foreach($term->patterns as $pattern)
        @php
            $root = $term->root->generateRoot($term);
            $arabic = $root[0];
            $translits = $root[1];
        @endphp

        {{--        TODO: put these in some kind of carousel, with the user's dialect first --}}
        @foreach ($translits as $dialectTranslit)
            @php
                $dialect = $dialectTranslit['dialect'];
                $translit = $dialectTranslit['translit'];
            @endphp

            @if ($pattern->form == '1')
                <x-conj.1
                    r1='{{ $arabic[0] }}'
                    r2='{{ $arabic[1] }}'
                    r3='{{ $arabic[2] }}'
                    r1tr='{{ $translit[0] }}'
                    r2tr='{{ $translit[1] }}'
                    r3tr='{{ $translit[2] }}'
                    form='{{ $pattern->pattern }}'
                    dialect='{{ $dialect }}'
                ></x-conj.1>

            @elseif (in_array($pattern->form, ['2', '3', '5', '6']))
                <x-conj.2536
                    r1='{{ $arabic[0] }}'
                    r2='{{ $arabic[1] }}'
                    r3='{{ $arabic[2] }}'
                    r1tr='{{ $translit[0] }}'
                    r2tr='{{ $translit[1] }}'
                    r3tr='{{ $translit[2] }}'
                    form='{{ $pattern->form }}{{ $pattern->pattern }}'
                    dialect='{{ $dialect }}'
                ></x-conj.2536>

            @elseif (in_array($pattern->form, ['4', '7', '8']))
                <x-conj.478
                    r1='{{ $arabic[0] }}'
                    r2='{{ $arabic[1] }}'
                    r3='{{ $arabic[2] }}'
                    r1tr='{{ $translit[0] }}'
                    r2tr='{{ $translit[1] }}'
                    r3tr='{{ $translit[2] }}'
                    form='{{ $pattern->form }}{{ $pattern->pattern }}'
                    dialect='{{ $dialect }}'
                ></x-conj.478>

            @elseif (in_array($pattern->form, ['9', 'X']))
                <x-conj.9X
                    r1='{{ $arabic[0] }}'
                    r2='{{ $arabic[1] }}'
                    r3='{{ $arabic[2] }}'
                    r1tr='{{ $translit[0] }}'
                    r2tr='{{ $translit[1] }}'
                    r3tr='{{ $translit[2] }}'
                    form='{{ $pattern->form }}{{ $pattern->pattern }}'
                    dialect='{{ $dialect }}'
                ></x-conj.9X>

            @elseif (in_array($pattern->form, ['2Q', '5Q']))
                <x-conj.Q
                    r1='{{ $arabic[0] }}'
                    r2='{{ $arabic[1] }}'
                    r3='{{ $arabic[2] }}'
                    r4='{{ $arabic[3] ?? null }}'
                    r1tr='{{ $translit[0] }}'
                    r2tr='{{ $translit[1] }}'
                    r3tr='{{ $translit[2] }}'
                    r4tr='{{ $translit[3] ?? null }}'
                    form='{{ $pattern->form }}{{ $pattern->pattern }}'
                    dialect='{{ $dialect }}'
                ></x-conj.Q>
            @endif
        @endforeach
    @endforeach
@endif
