@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel">
        <a href="{{ route('audios.record') }}" class="feature-callout">Record Your Own! -></a>
        <h1>{{ __('audios') }}</h1>

        @include('community.audios._filters')
    </div>
@endsection

@section('content')
    @if($totalCount > 0)
        <div class="audios-list">
            <div class="featured-title l">{{ __('all') }}</div>
            @if(!empty(array_filter([
                    'gender' => request('gender'),
                    'dialect' => request('dialect'),
                    'location' => request('location'),
                    ])))
                <x-tip>
                    <p>Displaying {{ number_format($totalCount) }} Audios by
                        <span style="text-transform: capitalize">{{ request('gender') }}</span> Speakers
                        {{ request('dialect') ? 'of '.\App\Models\Dialect::find(request('dialect'))->name : '' }}
                        {{ request('location') ? 'from '.\App\Models\Location::find(request('location'))->name_en : '' }}
                        in the Audio Library.
                    </p>
                </x-tip>

            @else
                <x-tip>
                    <p>Displaying all {{ number_format($totalCount) }} Audios in the Audio Library.</p>
                </x-tip>
            @endif

            @foreach($audios as $audio)
                <x-pronunciation-item :pronunciation="$audio->pronunciation" :audio="$audio"/>
            @endforeach
        </div>

    @else
        <x-tip>
            <p>No results matching this query.</p>
        </x-tip>
    @endif

    {{ $audios->appends(request()->query())->links() }}

@endsection
