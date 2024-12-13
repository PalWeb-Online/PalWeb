{{-- todo: turn into component & move loop into `show` view? --}}

@foreach ($pronunciations as $pronunciation)
    <div class="pronunciation-item-wrapper">
        <div class="pronunciation-item">
            <div class="pronunciation-item-dialect">{{ $pronunciation->dialect->name }}</div>
            <div class="pronunciation-item-phonology">
                {{ $pronunciation->borrowed == true ? '(Borrowed)' : '' }}
                {{ $pronunciation->translit }}
                —
                {{ $pronunciation->phonemic }}
                {{ $pronunciation->phonetic }}
            </div>
        </div>
        @if($pronunciation->audio_count > 0)
            <div class="pronunciation-audios">
                @foreach ($pronunciation->audios as $audio)
                    <x-audio-item :audio="$audio"/>
                @endforeach

            </div>
            @if(!request()->routeIs('terms.audios') && $pronunciation->audio_count > 1)
                <a href="{{ route('terms.audios', $term) }}">+</a>
            @endif
        @endif
    </div>
@endforeach
