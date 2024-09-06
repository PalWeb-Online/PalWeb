<div class="terms-featured-wrapper">
    <div class="terms-featured-daily">
        <div class="featured-title l" style="text-transform: none">Word of the Day</div>
        <div class="term-container-head">
            <div class="term-headword">
                <x-term-head :term="$wordOfTheDay" />

                <div>{{ __($wordOfTheDay->category) }}.
                    @include('terms._attributes', ['attributes' => $wordOfTheDay->attributes->pluck('attribute')->toArray()])

                    @if($wordOfTheDay->inflections->firstWhere('form', 'cnst'))
                        <span style="font-weight: 400">construct:</span>
                        {{ $wordOfTheDay->inflections->firstWhere('form', 'cnst')->inflection }}
                        ({{ $wordOfTheDay->inflections->firstWhere('form', 'cnst')->translit }})
                    @endif

                    @if($wordOfTheDay->category === 'verb')
                        @foreach($wordOfTheDay->patterns->pluck('form')->unique() as $form)
                            <a href="{{ route('wiki.show', 'verb-forms') }}" target="_blank"
                               style="font-style: italic">form {{ $form }}.</a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="terms-featured-daily-body">
            <div class="term-container-glosses">
                @foreach ($wordOfTheDay->glosses as $index => $gloss)
                    <div class="gloss-li-container">
                        <div class="gloss-li">
                            <div class="gloss-li-label">
                                {{ $index+1 }}
                            </div>

                            @isset($gloss->attribute)
                                <div class="chart-item {{ $gloss->type }}" style="margin: 0 0.8rem 0 0">
                                    <div class="chart-title">{{ $gloss->attribute }}</div>
                                    @isset($gloss->structure)
                                        <div>{{ $gloss->structure }}</div>
                                    @endisset
                                </div>
                            @endisset

                            <div class="gloss-li-content">
                                <div class="gloss-li-content-gloss">
                                    {{ $gloss->gloss }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($wordOfTheDay->image != '')
                <figure class="term-image">
                    <a href="{{ route('terms.show', $wordOfTheDay) }}">
                        <img alt="Term Image" src="{{ $wordOfTheDay->image }}">
                    </a>
                </figure>
            @endif
        </div>
    </div>

    <div class="terms-featured-latest">
        <div class="featured-title m" style="text-transform: none">Latest</div>
        @foreach($latestTerms as $wordOfTheDay)
            <a href="{{ route('terms.show', $wordOfTheDay) }}"
               data-tippy-term data-tippy-content="{{ $wordOfTheDay->glosses[0]->gloss }}">
                {{ $wordOfTheDay->term }}
            </a>
        @endforeach
    </div>
</div>
