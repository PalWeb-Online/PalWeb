@extends ('layouts.main')

@section('content')

    <x-page-head>
        <x-link :href="route('terms.index')">{{ __('dictionary') }}</x-link>
        <x-link :href="route('terms.show', $terms[0])">{{ __('view') }}</x-link>

        @if(request()->routeIs('terms.usages'))
            <x-link :href="route('terms.usages', $terms[0])">{{ __('usages') }}</x-link>
        @endif
    </x-page-head>

    @foreach($terms as $term)
        <div class="term-container">
            @if($term->isPinned())
                <img class="pin" src="{{ asset('img/pin.svg') }}" alt="pin"/>
            @endif

            <div class="term-container-head">
                <div class="term-headword">
                    <div class="term-headword-arb">{{ $term->term }}</div>
                    <div class="term-headword-eng">({{ $term->translit }})</div>

                    <img class="play" src="{{ asset('img/play.svg') }}" alt="play"
                         onclick="{{ $term->pronunciations[0]->audify() }}.play()"/>

                    <x-context-actions>
                        <x-term-actions :term="$term" :user="auth()->user()"/>
                    </x-context-actions>

                    <div>{{ __($term->category) }}.

                        @include('terms._attributes', ['attributes' => $term->attributes->pluck('attribute')->toArray()])

                        @if($term->inflections->firstWhere('form', 'cnst'))
                            <span style="font-weight: 400">construct:</span>
                            {{ $term->inflections->firstWhere('form', 'cnst')->inflection }}
                            ({{ $term->inflections->firstWhere('form', 'cnst')->translit }})
                        @endif

                        @if($term->category === 'verb')
                            @foreach($term->patterns->pluck('form')->unique() as $form)
                                <a href="{{ route('wiki.show', 'verb-forms') }}" target="_blank"
                                   style="font-style: italic">form {{ $form }}.</a>
                            @endforeach
                        @endif
                    </div>


                </div>

                @if(count($term->spellings) + count($term->variants) + count($term->references) > 0)
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
                                        ({{ $variant->translit }})</a>
                                @endforeach
                            </div>
                        @endif

                        @if(count($term->references) > 0)
                            <div>
                                <span style="font-weight: 400">see:</span>
                                @foreach($term->references as $reference)
                                    <a href="{{ route('terms.show', $reference)}}">{{ $reference->term }}
                                        {{ $reference->translit }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            @unless(request()->routeIs('terms.usages'))
                <div class="term-container-data">
                    <div class="term-etymology">
                        <div class="featured-title">{{ __('etymology') }}</div>
                        @include('terms._etymology')
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
            @endunless

            <div class="term-container-glosses">
                @foreach ($term->glosses as $i => $gloss)
                    <div class="gloss-li-container">
                        <div class="gloss-li">
                            <div class="gloss-li-label">
                                {{ $i + 1 }}
                            </div>

                            <div class="gloss-li-content">
                                @foreach($gloss->attributes as $attribute)
                                    <div class="gloss-li-attribute">
                                        @isset($attribute->category)
                                            [{{ $attribute->category }}]
                                        @endisset
                                        {{ $attribute->attribute }}
                                    </div>
                                @endforeach

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

                                @if(count($gloss->valences) > 0)
                                    @foreach($gloss->valences as $pair)
                                        <div>
                                            {{ $pair->pivot->type }}
                                            <a href="{{ route('terms.show', $pair) }}">{{ $pair->term }}
                                                ({{ $pair->translit }})
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                        </div>

                        @if(count($term->sentences($gloss->id)->get()) > 0)
                            @if(request()->routeIs('terms.usages'))
                                @foreach ($term->sentences($gloss->id)->get() as $sentence)
                                    <x-sentence :sentence="$sentence" :currentTerm="$term->id"/>
                                @endforeach
                            @else
                                @foreach ($term->sentences($gloss->id)->take(2)->get() as $sentence)
                                    <x-sentence :sentence="$sentence" :currentTerm="$term->id"/>
                                @endforeach
                                @if(count($term->sentences($gloss->id)->get()) > 2)
                                    <a href="{{ route('terms.usages', $term) }}">See All Usages
                                        ({{ count($term->sentences($gloss->id)->get()) }})</a>
                                @endif
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>

            @unless(request()->routeIs('terms.usages'))

                @include('terms._inflections')

                @if($term->image != '')
                    <figure class="term-image">
                        <img alt="Term Image" src="{{ $term->image }}">
                    </figure>
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
            @endunless
        </div>

        @if(!request()->routeIs('terms.usages') && $term->root)
            @include('terms._root')
        @endif

    @endforeach
@endsection
