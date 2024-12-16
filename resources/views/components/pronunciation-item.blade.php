@props([
    'pronunciation' => false,
    'audio' => false,
])

@if($pronunciation)
    <div class="pronunciation-item-wrapper">
        <div class="pronunciation-item">
            @if($audio)
                <div class="pronunciation-item-term">
                    <a href="{{ route('terms.show', $pronunciation->term) }}">{{ $pronunciation->term->term }}</a>
                </div>
            @endif
            <div class="pronunciation-item-dialect">{{ $pronunciation->dialect->name }}</div>
            <div class="pronunciation-item-phonology">
                {{ $pronunciation->borrowed === true ? '(Borrowed)' : '' }}
                {{ $pronunciation->translit }}
                —
                {{ $pronunciation->phonemic }}
                {{ $pronunciation->phonetic }}
            </div>
        </div>

        @if($audio)
            <div class="pronunciation-audios">
                <x-audio-item :audio="$audio"/>
            </div>

        @elseif($pronunciation->audio_count > 0)
            <div class="pronunciation-audios">
                @foreach ($pronunciation->audios as $audio)
                    <x-audio-item :audio="$audio"/>
                @endforeach
            </div>
            @if(!request()->routeIs('terms.audios') && $pronunciation->audio_count > 1)
                <a href="{{ route('terms.audios', $pronunciation->term) }}">+</a>
            @endif
        @endif
    </div>
@endif
