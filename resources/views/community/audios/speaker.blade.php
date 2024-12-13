@extends ('layouts.main')

@section('content')
    <x-page-head>
        <x-link :href="route('audios.index')">{{ __('audios') }}</x-link>
        <x-link :href="route('audios.speaker', $speaker)">{{ __('speaker') }}</x-link>
    </x-page-head>

    @include('users._speaker')

    @if(count($audios) > 0)

        <div class="featured-title m">{{ __('all') }}</div>

        <x-tip>
            <p>Displaying all {{ number_format(count($audios)) }} Audios by {{ $speaker->user->private ? 'this Speaker' : $speaker->user->name }}.</p>
        </x-tip>

        <div class="audios-list">
            @foreach($audios as $audio)
                <div class="pronunciation-item-wrapper">
                    <div class="pronunciation-item">
                        <a class="pronunciation-item-dialect"
                           href="{{ route('terms.show', $audio->pronunciation->term) }}">{{ $audio->pronunciation->term->term }}</a>
                        <div class="pronunciation-item-phonology">
                            <div>
                                {{ $audio->pronunciation->borrowed == true ? '(Borrowed)' : '' }}
                                {{ $audio->pronunciation->translit }}
                                —
                                {{ $audio->pronunciation->phonemic }}
                                {{ $audio->pronunciation->phonetic }}
                            </div>
                        </div>
                    </div>
                    <div class="pronunciation-audios">
                        <x-audio-item :audio="$audio" />
                    </div>
                </div>
            @endforeach
        </div>
        {{ $audios->links() }}

    @else
        <x-tip>
            <p>{{ $speaker->user->private ? 'This Speaker' : $speaker->user->name }} has not uploaded any Audios yet.</p>
        </x-tip>
    @endif
@endsection
