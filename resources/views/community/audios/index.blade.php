@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel">
        <a href="{{ route('audios.record') }}" class="feature-callout">Record Your Own! -></a>
        <h1>{{ __('audios') }}</h1>
    </div>
@endsection

@section('content')
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
                    <x-audio-item :audio="$audio"/>
                </div>
            </div>
        @endforeach
    </div>
    {{ $audios->links() }}

@endsection
