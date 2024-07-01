<div class="terms-featured-wrapper">
    <div class="terms-featured-daily">
        <div class="featured-title l" style="text-transform: none">Word of the Day</div>
        <div class="term-container-head">
            <img class="popout" src="{{ asset('/img/sunflower.svg') }}" alt="Sunflower"/>

            <div class="term-headword">
                <div class="term-headword-arb">{{ $wordOfTheDay->term }}</div>
                <div class="term-headword-eng">({{ $wordOfTheDay->translit }})</div>

                <img class="play" src="{{ asset('img/play.svg') }}" width="28" alt="play"
                     onclick="{{ $wordOfTheDay->pronunciations[0]->audify() }}.play()"/>

                <script type="text/javascript">
                    var {{ $wordOfTheDay->pronunciations[0]->audify() }} = new Howl({
                        src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/{{ $wordOfTheDay->pronunciations[0]->audify() }}.mp3']
                    });
                </script>

                <x-context-actions>
                    <a href="{{ route('terms.show', $wordOfTheDay) }}" target="_blank">View Term</a>
                    <x-term-actions :term="$wordOfTheDay" :user="auth()->user()"/>
                </x-context-actions>

                <div>{{ __($wordOfTheDay->category) }}.
                    @foreach($wordOfTheDay->attributes as $attribute)
                        @if($attribute->attribute !== 'idiom')
                            <span style="font-weight: 400">{{ $attribute->attribute }}.</span>
                        @endif
                    @endforeach
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
