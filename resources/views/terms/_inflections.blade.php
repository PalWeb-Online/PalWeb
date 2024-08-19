@php
    $hostForms = $term->inflections->whereIn('form', ['genitive', 'accusative'])->all();
@endphp

@if (count($hostForms) > 0)

    <div x-data="{ activeIndex: 0, patterns: {{ count($hostForms) }} }" class="inflection-carousel">

        @foreach ($hostForms as $index => $hostForm)
            <div class="carousel-item" x-show="activeIndex === {{ $index }}" x-cloak>

                @if(count($hostForms) > 1)
                    <div class="carousel-item-head">
                        <button @click="activeIndex = (activeIndex > 0) ? activeIndex - 1 : patterns - 1">
                            &larr;
                        </button>

                        Variant {{ $index + 1 }}

                        <button @click="activeIndex = (activeIndex < patterns - 1) ? activeIndex + 1 : 0">
                            &rarr;
                        </button>
                    </div>
                @endif

                <x-chart.enclitics
                    host="{{ $hostForm->inflection }}"
                    translit="{{ $hostForm->translit }}"
                    form="{{ $hostForm->form }}"
                />
            </div>
        @endforeach
    </div>

@elseif ($term->category == 'verb')
    @php
        $root = $term->root->generateRoot($term);
        $arabic = $root[0];
        $translits = $root[1];
    @endphp

    <div x-data="{ activeIndex: 0, patterns: {{ $term->patterns->count() * count($translits) }} }"
         class="inflection-carousel">

        @foreach($term->patterns as $index => $pattern)
            @foreach ($translits as $dialectIndex => $dialectTranslit)
                @php
                    $translit = $dialectTranslit['translit'];
                @endphp

                <div class="carousel-item" x-show="activeIndex === {{ $index * count($translits) + $dialectIndex }}"
                     x-cloak>
                    <div class="carousel-item-head">
                        @if ($term->patterns->count() * count($translits) > 1)
                            <button @click="activeIndex = (activeIndex > 0) ? activeIndex - 1 : patterns - 1">
                                &larr;
                            </button>
                        @endif

                        <div style="flex-grow: 1">{{ $dialectTranslit['dialect'] }} {{ $pattern->form === '1' ? $pattern->pattern : $pattern->form . $pattern->pattern }}</div>

                        @if ($term->patterns->count() * count($translits) > 1)
                            <button @click="activeIndex = (activeIndex < patterns - 1) ? activeIndex + 1 : 0">
                                &rarr;
                            </button>
                        @endif
                    </div>

                    @if ($pattern->form == '1')
                        <x-chart.1
                            r1='{{ $arabic[0] }}'
                            r2='{{ $arabic[1] }}'
                            r3='{{ $arabic[2] }}'
                            r1tr='{{ $translit[0] }}'
                            r2tr='{{ $translit[1] }}'
                            r3tr='{{ $translit[2] }}'
                            form='{{ $pattern->pattern }}'
                        ></x-chart.1>

                    @elseif (in_array($pattern->form, ['2', '3', '5', '6']))
                        <x-chart.2536
                            r1='{{ $arabic[0] }}'
                            r2='{{ $arabic[1] }}'
                            r3='{{ $arabic[2] }}'
                            r1tr='{{ $translit[0] }}'
                            r2tr='{{ $translit[1] }}'
                            r3tr='{{ $translit[2] }}'
                            form='{{ $pattern->form }}{{ $pattern->pattern }}'
                        ></x-chart.2536>

                    @elseif (in_array($pattern->form, ['4', '7', '8']))
                        <x-chart.478
                            r1='{{ $arabic[0] }}'
                            r2='{{ $arabic[1] }}'
                            r3='{{ $arabic[2] }}'
                            r1tr='{{ $translit[0] }}'
                            r2tr='{{ $translit[1] }}'
                            r3tr='{{ $translit[2] }}'
                            form='{{ $pattern->form }}{{ $pattern->pattern }}'
                        ></x-chart.478>

                    @elseif (in_array($pattern->form, ['9', 'X']))
                        <x-chart.9X
                            r1='{{ $arabic[0] }}'
                            r2='{{ $arabic[1] }}'
                            r3='{{ $arabic[2] }}'
                            r1tr='{{ $translit[0] }}'
                            r2tr='{{ $translit[1] }}'
                            r3tr='{{ $translit[2] }}'
                            form='{{ $pattern->form }}{{ $pattern->pattern }}'
                        ></x-chart.9X>

                    @elseif (in_array($pattern->form, ['2Q', '5Q']))
                        <x-chart.Q
                            r1='{{ $arabic[0] }}'
                            r2='{{ $arabic[1] }}'
                            r3='{{ $arabic[2] }}'
                            r4='{{ $arabic[3] ?? null }}'
                            r1tr='{{ $translit[0] }}'
                            r2tr='{{ $translit[1] }}'
                            r3tr='{{ $translit[2] }}'
                            r4tr='{{ $translit[3] ?? null }}'
                            form='{{ $pattern->form }}{{ $pattern->pattern }}'
                        ></x-chart.Q>
                    @endif
                </div>
            @endforeach
        @endforeach
    </div>
@endif
