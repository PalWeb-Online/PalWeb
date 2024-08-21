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

                        <div
                            style="flex-grow: 1">{{ $dialectTranslit['dialect'] }} {{ $pattern->form === '1' ? $pattern->pattern : $pattern->form . $pattern->pattern }}</div>

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

        @if(count($term->inflections) > 0)
            <div class="inflection-chart-wrapper derived-terms">
                <div class="inflection-chart">
                    @if($term->inflections->firstWhere('form', 'ap'))
                        <a href="#" class="inflection-chart-item">
                            <div>AP</div>
                            <div>
                                <div>{{ $term->inflections->firstWhere('form', 'ap')->inflection }}</div>
                                <div>{{ $term->inflections->firstWhere('form', 'ap')->translit }}</div>
                            </div>
                        </a>
                    @endif
                    @if($term->inflections->firstWhere('form', 'pp'))
                        <a href="#" class="inflection-chart-item">
                            <div>PP</div>
                            <div>
                                <div>{{ $term->inflections->firstWhere('form', 'pp')->inflection }}</div>
                                <div>{{ $term->inflections->firstWhere('form', 'pp')->translit }}</div>
                            </div>
                        </a>
                    @endif
                    @if($term->inflections->firstWhere('form', 'nv'))
                        <a href="#" class="inflection-chart-item">
                            <div>NV</div>
                            <div>
                                <div>{{ $term->inflections->firstWhere('form', 'nv')->inflection }}</div>
                                <div>{{ $term->inflections->firstWhere('form', 'nv')->translit }}</div>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

@elseif(count($term->inflections) > 0)

    @if (in_array($term->category, ['noun', 'adjective']))
        @php
            $type = 'singular';
            $isMasculine = false;

            foreach ($term->attributes->pluck('attribute')->toArray() as $attribute) {
                if ($attribute === 'collective') {
                    $type = 'collective';
                    break;
                }
            }

            if (count($term->inflections->where('form', 'fem')) > 0) {
                $type = 'masculine';
            }
        @endphp

        <div class="inflection-carousel">
            <div class="carousel-item">
                <x-chart.inflection :term="$term" :type="$type"/>
            </div>
        </div>
    @endif
    @if (count($term->inflections->where('form', 'resp')) > 0)
        <div class="activity-dialog" style="margin-block: 0;">
            <x-dialog-line speaker="دعاء" :arb="$term->term" :eng="$term->translit" audio/>
            @foreach($term->inflections->where('form', 'resp')->all() as $response)
                <x-dialog-line ltr speaker="جواب" :arb="$response->inflection" :eng="$response->translit" audio/>
            @endforeach
        </div>
    @endif

@endif
