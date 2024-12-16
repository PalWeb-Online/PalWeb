@extends ('layouts.main')

@section('content')
    <x-page-head>
        <x-link :href="route('audios.index')">{{ __('audios') }}</x-link>
        <x-link :href="route('audios.speaker', $speaker)">{{ __('speaker') }}</x-link>
    </x-page-head>

    @include('users._speaker')

    @if(count($audios) > 0)
        <div class="audios-list">
            <div class="featured-title m">All</div>
            @foreach($audios as $audio)
                <x-pronunciation-item :pronunciation="$audio->pronunciation" :audio="$audio"/>
            @endforeach
        </div>
        {{ $audios->links() }}

    @else
        <x-tip>
            <p>{{ $speaker->user->private ? 'This Speaker' : $speaker->user->name }} has not uploaded any Audios yet.</p>
        </x-tip>
    @endif
@endsection
