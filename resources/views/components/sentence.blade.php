@props([
    'sentence' => false,
    'currentTerm' => false,
    'size' => 'm',
    'eng' => false,
])

{{-- if the Sentence model exists --}}
@if($sentence)
    <div class="sentence-wrapper">
        <div class="sentence {{ $size }} audio" onclick="{{ $sentence->audify() }}.play()" tabindex="0">
            <div class="sentence-arb">
                @foreach($sentence->allTerms() as $term)

                    @if(!$term->id)
                        <x-sentence-term :arb="$term->sent_term" :eng="$term->sent_translit"/>
                    @else
                        <x-sentence-term :term="\App\Models\Term::find($term->id)"
                                         :arb="$term->sent_term"
                                         :eng="$term->sent_translit"
                                         :isCurrent="$currentTerm === $term->id"
                        />
                    @endif
                @endforeach
            </div>
            <div class="sentence-eng">
                {{ $sentence->trans }}
            </div>

            @if($sentence->isPinned())
                <img class="pin" src="{{ asset('img/pin.svg') }}" alt="pin"/>
            @endif
        </div>

        <x-context-actions>
            @unless(request()->routeIs('sentences.show'))
                <a href="{{ route('sentences.show', $sentence) }}" target="_blank">View Sentence</a>
            @endunless
            <x-sentence-actions :sentence="$sentence"/>
        </x-context-actions>
    </div>
    <script type="text/javascript">
        var {{ $sentence->audify() }} = new Howl({src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/{{ $sentence->audify() }}.mp3']});
    </script>

    {{-- if the Sentence is built in the view --}}
@elseif($eng)
    <div class="sentence-wrapper" style="justify-self: center">
        <div class="sentence {{ $size }}">
            <div class="sentence-arb">
                {{ $slot }}
            </div>
            <div class="sentence-eng">
                {{ $eng }}
            </div>
        </div>
    </div>

    {{-- if there is no data for the Sentence; i.e. if the Sentence model should be retrieved, but doesn't exist yet --}}
@else
    <div class="sentence coming-soon">
        <div class="feature-callout">
            {{ __('coming soon') }}
        </div>
    </div>
@endif
