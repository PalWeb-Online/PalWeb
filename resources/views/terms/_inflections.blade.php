@if(count($term->inflections->where('form', 'host')) > 0)
    @if($term->slug === 'preposition-b-')
        <x-conj.b/>
    @else
        @foreach ($term->inflections->where('form', 'host') as $hostForm)
            <x-conj.inflection host="{{ $hostForm->inflection }}" translit="{{ $hostForm->translit }}"/>
        @endforeach
    @endif

@elseif($term->category == 'verb')
    @php
        $root = $term->root->generateRoot($term);
        $arabic = $root[0];
        $translits = $root[1];
    @endphp

    <div x-data="{ activeIndex: 0, patterns: {{ $term->patterns->count() * count($translits) }} }" class="inflection-carousel">

        @foreach($term->patterns as $index => $pattern)
            @foreach ($translits as $dialectIndex => $dialectTranslit)
                @php
                    $translit = $dialectTranslit['translit'];
                @endphp

                <div class="carousel-item" x-show="activeIndex === {{ $index * count($translits) + $dialectIndex }}"
                     x-cloak>
                    <div class="carousel-item-head">
                        <button @click="activeIndex = (activeIndex > 0) ? activeIndex - 1 : patterns - 1">
                            &larr;
                        </button>

                        {{ $dialectTranslit['dialect'] }} {{ $pattern->pattern }}

                        <button @click="activeIndex = (activeIndex < patterns - 1) ? activeIndex + 1 : 0">
                            &rarr;
                        </button>
                    </div>

                    @if ($pattern->form == '1')
                        <x-conj.1
                            r1='{{ $arabic[0] }}'
                            r2='{{ $arabic[1] }}'
                            r3='{{ $arabic[2] }}'
                            r1tr='{{ $translit[0] }}'
                            r2tr='{{ $translit[1] }}'
                            r3tr='{{ $translit[2] }}'
                            form='{{ $pattern->pattern }}'
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
                        ></x-conj.Q>
                    @endif
                </div>
            @endforeach
        @endforeach
    </div>
@endif
