@if(isset($term))
    @php
        $term->pronunciation = $term->pronunciations->first();

        if(auth()->check()) {
            $pronunciation = $term->pronunciations->firstWhere('dialect_id', auth()->user()->dialect_id);
            $pronunciation && $term->pronunciation = $pronunciation;
        }
    @endphp

    <div class="term-li-container">
        <div class="term-li-wrapper">
            <x-context-actions>
                <x-term-actions :term="$term"/>
            </x-context-actions>

            <div class="term-li">
                <div class="arb audio" onclick="{{ $term->pronunciation->audify() }}.play()"
                     data-tippy-translit data-tippy-content="{{ $term->pronunciation->translit }}">
                    {{ $term->term }}
                </div>
                <script type="text/javascript">
                    var {{ $term->pronunciation->audify() }} = new Howl({src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/{{ $term->pronunciation->audify() }}.mp3']})
                </script>

                <div class="eng">
                    {{ \Illuminate\Support\Str::limit($gloss->gloss ?? $term->glosses[0]->gloss, 48) }}
                </div>

                @if($term->isPinned())
                    <img class="pin" src="{{ asset('img/pin.svg') }}" alt="pin"/>
                @endif
            </div>
        </div>

        {{ $slot }}
    </div>

@elseif(isset($subterm))
    <div class="term-li subterm">
        <div class="arb">{{ $arb }}</div>
        <div class="eng">{{ $eng }}</div>
    </div>

@elseif(isset($arb))
    <div class="term-li-container">
        <div class="term-li-wrapper">
            <div class="term-li">
                <div class="arb">{{ $arb }}</div>
                <div class="eng">{{ $eng }}</div>
            </div>
        </div>
        {{ $slot }}
    </div>
@else
    <div class="term-li coming-soon">
        <div class="feature-callout">
            {{ __('coming soon') }}
        </div>
    </div>
@endif
