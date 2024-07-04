@extends ('layouts.main')

@section('content')

    <x-page-head>
        <x-link :href="route('terms.index')">{{ __('dictionary') }}</x-link>
        <x-link :href="route('terms.show', $terms[0])">{{ __('view') }}</x-link>
    </x-page-head>

    @foreach($terms as $term)
        <div class="term-container">
            @php
                $parentTerm = false;
                $asInflection = App\Models\Inflection::whereIn('form', ['ap','pp','nv'])->where('translit', $term->translit);
                $asInflection->exists() && $parentTerm = App\Models\Term::firstWhere('id', $asInflection->first()->term_id);
            @endphp

            @if($term->isPinned())
                <img class="pin" src="{{ asset('img/pin.svg') }}" alt="pin"/>
            @endif

            <div class="term-container-head">
                <div class="term-headword">
                    <div class="term-headword-arb">{{ $term->term }}</div>
                    <div class="term-headword-eng">({{ $term->translit }})</div>

                    <img class="play" src="{{ asset('img/play.svg') }}" alt="play"
                         onclick="{{ $term->pronunciations[0]->audify() }}.play()"/>

                    @auth
                        <x-context-actions>
                            <x-term-actions :term="$term" :user="auth()->user()"/>
                        </x-context-actions>
                    @endauth

                    <div>{{ __($term->category) }}.
                        @foreach($term->attributes as $attribute)
                            @if($attribute->attribute !== 'idiom')
                                <span style="font-weight: 400">{{ $attribute->attribute }}.</span>
                            @endif
                        @endforeach
                    </div>

                    {{--                    TODO: Use this file to organize attributes & links --}}
                    {{--                    @if(count($term->attributes) > 0)--}}
                    {{--                        @include('terms._attributes', ['attributes' => $term->attributes])--}}
                    {{--                    @endif--}}

                    @if($term->category === 'verb')
                        @foreach($term->patterns as $pattern)
                            @if($pattern->type === 'verbal')
                                <div>FORM {{ $pattern->form }} ({{ $pattern->pattern }})</div>
                            @endif
                        @endforeach
                    @endif
                    @if($parentTerm)
                        <a href="{{ route('terms.show', $parentTerm) }}"
                           class="chart-item {{ $asInflection->first()->form }}">
                            <div class="chart-title">{{ $asInflection->first()->form }}</div>
                            <div>{{ $parentTerm->term }} ({{ $parentTerm->translit }})</div>
                        </a>
                    @endif
                </div>

                <div class="term-references">
                    @if(count($term->spellings) > 0)
                        <div>
                            or
                            @foreach($term->spellings as $spelling)
                                <span style="font-weight: 700">{{ $spelling->spelling }}</span>
                            @endforeach
                        </div>
                    @endif

                    @if(count($term->variants) > 0)
                        <div>
                            <span style="font-weight: 400">alt.</span>
                            @foreach($term->variants as $variant)
                                <a href="{{ route('terms.show', $variant) }}">{{ __($variant->term) }}
                                    ({{ $variant->translit }})
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if(count($term->references) > 0)
                        <div>
                            <span style="font-weight: 400">see:</span>
                            @foreach($term->references as $reference)
                                <a href="{{ route('terms.show', $reference)}}">{{ $reference->term }}
                                    {{ $reference->translit }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                @unless(count($term->inflections->where('form', '!=', 'host')) == 0 and !$parentTerm)
                    <div class="term-inflections">
                        @foreach($term->inflections as $inflection)
                            <x-info-button label="{{ $inflection->form }}"
                                           arb="{{ $inflection->inflection }}"
                                           eng="{{ $inflection->translit }}"
                            />

                            {{--                    TODO: Support audio & links in x-info-button    --}}
                            {{--                    @php--}}
                            {{--                        $inflectionTerm = $term->firstWhere('translit', $inflection->translit);--}}
                            {{--                    @endphp--}}
                            {{--                    @if($inflectionTerm)--}}
                            {{--                        <a href="{{ route('terms.show', $inflectionTerm) }}"--}}
                            {{--                           class="chart-item {{ $inflection->form }}">--}}
                            {{--                            <div class="chart-title">--}}
                            {{--                                {{ $inflection->form }}--}}
                            {{--                            </div>--}}
                            {{--                            <div>{{ $inflection->inflection }} ({{ $inflection->translit }})--}}
                            {{--                            </div>--}}
                            {{--                        </a>--}}
                            {{--                    @else--}}
                            {{--                        <a class="chart-item audio {{ $inflection->form }}"--}}
                            {{--                           onclick="{{ $inflection->audify() }}.play()">--}}
                            {{--                            <div class="chart-title">{{ $inflection->form }}</div>--}}
                            {{--                            <div>{{ $inflection->inflection }} ({{ $inflection->translit }})--}}
                            {{--                            </div>--}}
                            {{--                        </a>--}}
                            {{--                        <script type="text/javascript">--}}
                            {{--                            var {{ $inflection->audify() }} = new Howl({src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/{{ $inflection->audify() }}.mp3']});--}}
                            {{--                        </script>--}}
                            {{--                    @endif--}}
                        @endforeach
                    </div>
                @endunless
            </div>

            <div class="term-container-data">
                <div class="term-etymology">
                    <div class="featured-title">{{ __('etymology') }}</div>
                    <div style="display: flex; align-items: center; gap: 1.6rem">

                        <div>
                            {{ __($term->etymology['type']) }} {{ $term->etymology['source'] ? __($term->etymology['source']) : '' }}
                        </div>

                        @php
                            $sortedPatterns = $term->patterns->sortByDesc('type');
                        @endphp
                        @foreach ($sortedPatterns as $pattern)
                            @if ($pattern->type === 'singular')
                                @if ($pattern->form)
                                    <div>
                                        Verbal Pattern: Form {{ $pattern->form }} {{ $pattern->pattern }}
                                    </div>
                                @else
                                    <div>
                                        Singular Pattern:
                                        <a href="{{ route('wiki.show', 'patterns') }}">{{ $pattern->pattern }}</a>
                                    </div>
                                @endif
                            @endif
                            @if ($pattern->type === 'plural')
                                <div>
                                    Plural Pattern:
                                    {{ $pattern->pattern }}
                                </div>
                            @endif
                        @endforeach
                    </div>

                    @if(count($term->components) > 0)
                        <div>
                            Idiom from
                            @foreach($term->components as $component)
                                <a href="{{ route('terms.show', $component) }}">{{ $component->term }}
                                    ({{ $component->translit }})</a>
                            @endforeach
                        </div>
                    @endif

                    @if(count($term->descendants) > 0)
                        <div>
                            Component of
                            @foreach($term->descendants as $descendant)
                                <a href="{{ route('terms.show', $descendant) }}">{{ $descendant->term }}
                                    ({{ $descendant->translit }})
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="term-pronunciation" x-data="{ open: false }">
                    <div class="featured-title">{{ __('pronunciation') }}</div>
                    @if(auth()->check())
                        @unless($userPronunciations->isEmpty())
                            @include('terms._pronunciations', ['pronunciations' => $userPronunciations])
                        @else
                            <div class="inline-chart" style="padding: 0">
                                <div
                                    style="background: rgba(255, 194, 14, 0.25); border-radius: 1.6rem; padding: 1.2rem; font-weight: 400; text-align: left">
                                    <b>WARNING</b> Your target dialect ({{ auth()->user()->dialect->name }}) does
                                    not use this term. Here are its pronunciations in other dialects.
                                </div>
                            </div>
                        @endunless

                        @unless($otherPronunciations->isEmpty())
                            <div x-show="open">
                                @include('terms._pronunciations', ['pronunciations' => $otherPronunciations])
                            </div>
                            <button @click="open = !open" x-text="open ? 'expand_less' : 'expand_more'"
                                    class="toggle-button material-symbols-rounded">
                            </button>
                        @endunless
                    @else
                        @include('terms._pronunciations', ['pronunciations' => $allPronunciations])
                    @endif
                </div>
            </div>

            <div class="term-container-glosses">
                @foreach ($term->glosses as $index => $gloss)

                    <div class="gloss-li-container">
                        <div class="gloss-li">
                            <div class="gloss-li-label">
                                {{ $index+1 }}
                            </div>

                            <div class="gloss-li-content">
                                @isset($gloss->attribute)
                                    <div class="gloss-li-attribute">
                                        [{{ $gloss->attribute }}] {{ $gloss->structure }}
                                    </div>
                                @endisset

                                <div class="gloss-li-content-gloss">
                                    {{ $gloss->gloss }}
                                </div>

                                @if(count($gloss->synonyms) > 0)
                                    <div>
                                        syn.
                                        @foreach($gloss->synonyms as $synonym)
                                            <a href="{{ route('terms.show', $synonym) }}">{{ $synonym->term }}
                                                ({{ $synonym->translit }})
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                @if(count($gloss->antonyms) > 0)
                                    <div>
                                        ant.
                                        @foreach($gloss->antonyms as $antonym)
                                            <a href="{{ route('terms.show', $antonym) }}">{{ $antonym->term }}
                                                ({{ $antonym->translit }})
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            @if(count($gloss->valences) > 0 || count($gloss->synonyms) > 0 || count($gloss->antonyms) > 0)
                                <div class="gloss-relatives">
                                    @if(count($gloss->valences) > 0)
                                        @foreach($gloss->valences as $pair)
                                            @php
                                                $pairType = $pair->pivot->type;
                                            @endphp
                                            <div class="gloss-relative">
                                                <div>{{ __('see') }}</div>
                                                <a href="{{ route('terms.show', $pair) }}"
                                                   class="chart-item {{ $pairType }}">
                                                    <div class="chart-title">{{ $pair->term }}</div>
                                                    <div>{{ $pair->translit }}</div>
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                        </div>

                        @if(count($gloss->sentences) > 0)
                            @foreach ($gloss->sentences as $sentence)
                                <x-sentence :sentence="$sentence" :currentTerm="$term->translit"/>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>

            @if($term->image != '')
                <figure class="term-image">
                    <img alt="Term Image" src="{{ $term->image }}">
                </figure>
            @endif

            @if($term->category == 'verb')
                @foreach($term->patterns as $pattern)
                    {{--                    <div class="data-box">--}}
                    {{--                        <div class="data-box-title" style="padding-right: 0.8rem">--}}
                    {{--                            <h2>{{ __('conjugation') }}</h2>--}}
                    {{--                            <h2 style="font-size: 2.0rem">{{ __('form') }} {{ $pattern->form }} <span--}}
                    {{--                                    style="text-transform: none">({{ $pattern->pattern }})</span></h2>--}}
                    {{--                        </div>--}}
                    {{--                        <div class="data-box-contents">--}}
                    @if ($pattern->form == '1')
                        <x-conj.1
                            r1='{{ $term->root->rootArray()[0][0] }}'
                            r2='{{ $term->root->rootArray()[0][1] }}'
                            r3='{{ $term->root->rootArray()[0][2] }}'
                            r1tr='{{ $term->root->rootArray()[1][0] }}'
                            r2tr='{{ $term->root->rootArray()[1][1] }}'
                            r3tr='{{ $term->root->rootArray()[1][2] }}'
                            form='{{ $pattern->pattern }}'
                        ></x-conj.1>

                    @elseif (in_array($pattern->form, ['2', '3', '5', '6']))
                        <x-conj.2536
                            r1='{{ $term->root->rootArray()[0][0] }}'
                            r2='{{ $term->root->rootArray()[0][1] }}'
                            r3='{{ $term->root->rootArray()[0][2] }}'
                            r1tr='{{ $term->root->rootArray()[1][0] }}'
                            r2tr='{{ $term->root->rootArray()[1][1] }}'
                            r3tr='{{ $term->root->rootArray()[1][2] }}'
                            form='{{ $pattern->form }}{{ $pattern->pattern }}'
                        ></x-conj.2536>

                    @elseif (in_array($pattern->form, ['4', '7', '8']))
                        <x-conj.478
                            r1='{{ $term->root->rootArray()[0][0] }}'
                            r2='{{ $term->root->rootArray()[0][1] }}'
                            r3='{{ $term->root->rootArray()[0][2] }}'
                            r1tr='{{ $term->root->rootArray()[1][0] }}'
                            r2tr='{{ $term->root->rootArray()[1][1] }}'
                            r3tr='{{ $term->root->rootArray()[1][2] }}'
                            form='{{ $pattern->form }}{{ $pattern->pattern }}'
                        ></x-conj.478>

                    @elseif (in_array($pattern->form, ['9', 'X']))
                        <x-conj.9X
                            r1='{{ $term->root->rootArray()[0][0] }}'
                            r2='{{ $term->root->rootArray()[0][1] }}'
                            r3='{{ $term->root->rootArray()[0][2] }}'
                            r1tr='{{ $term->root->rootArray()[1][0] }}'
                            r2tr='{{ $term->root->rootArray()[1][1] }}'
                            r3tr='{{ $term->root->rootArray()[1][2] }}'
                            form='{{ $pattern->form }}{{ $pattern->pattern }}'
                        ></x-conj.9X>

                    @elseif (in_array($pattern->form, ['2Q', '5Q']))
                        <x-conj.Q
                            r1='{{ $term->root->rootArray()[0][0] }}'
                            r2='{{ $term->root->rootArray()[0][1] }}'
                            r3='{{ $term->root->rootArray()[0][2] }}'
                            r4='{{ $term->root->rootArray()[0][3] }}'
                            r1tr='{{ $term->root->rootArray()[1][0] }}'
                            r2tr='{{ $term->root->rootArray()[1][1] }}'
                            r3tr='{{ $term->root->rootArray()[1][2] }}'
                            r4tr='{{ $term->root->rootArray()[1][3] }}'
                            form='{{ $pattern->form }}{{ $pattern->pattern }}'
                        ></x-conj.Q>
                    @endif
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                @endforeach
            @endif

            @if(count($term->inflections->where('form', 'host')) > 0)
                @php
                    $hostForms = $term->inflections->where('form', 'host');
                @endphp
                @if($term->translit === 'b-')
                    <x-conj.b/>
                @else
                    @foreach ($hostForms as $hostForm)
                        <x-conj.inflection host="{{ $hostForm->inflection }}" translit="{{ $hostForm->translit }}"/>
                    @endforeach
                @endif
            @endif

            @isset($term->usage)
                <div class="user-wrapper">
                    <div class="user-avatar">
                        <img src="{{ asset('img/avatars/' . \App\Models\User::find(1)->avatar) }}"
                             alt="Profile Picture"/>
                    </div>
                    <div class="user-comment">
                        <div class="user-comment-head">Editor's Note</div>
                        <div class="user-comment-body">
                            <div class="user-comment-body-content">
                                {{ $term->usage }}
                            </div>
                            <div class="user-comment-body-data">
                                — {{  \App\Models\User::find(1)->name }} ({{ \App\Models\User::find(1)->username }})
                            </div>
                        </div>
                    </div>
                </div>
            @endisset

            @if(count($term->decks->where('private', '!=', 1)) > 0)
                <div class="term-container-decks">
                    <div class="featured-title s">Featured In</div>

                    <div class="decks-list">
                        @foreach($term->decks->where('private', '!=', 1) as $deck)
                            <x-deck-li :deck="$deck"/>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @if($term->root)
            @php
                $rootTerms = $term->root->terms->sortBy('term');
            @endphp

            <div class="term-root" x-data="{ open: false }">
                <div class="term-root-head">
                    <div class="term-root-head-arb">{{ $term->root->showRoot() }}</div>
                    <div class="term-root-head-eng">({{ $term->root->transRoot() }})</div>
                </div>
                @unless(count($rootTerms) == 1)
                    <div class="term-root-body" x-show="open">
                        @foreach($rootTerms as $rootTerm)
                            @unless($rootTerm->id == $term->id)
                                <a href="{{ route('terms.show', $rootTerm) }}">{{ $rootTerm->term }}
                                    ({{ $rootTerm->translit }})
                                </a>
                            @endunless
                        @endforeach
                    </div>
                @endunless
                @unless(count($rootTerms) == 1)
                    <button @click="open = !open" x-text="open ? 'expand_less' : 'expand_more'"
                            class="material-symbols-rounded">
                    </button>
                @endunless
            </div>
        @endif

    @endforeach
@endsection
