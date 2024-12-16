@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel">
        <a href="{{ route('audios.record') }}" class="feature-callout">Record Your Own! -></a>
        <h1>{{ __('audios') }}</h1>
    </div>
@endsection

@section('content')
    <form class="audio-filters" method="GET" action="{{ route('audios.index') }}">
        <div class="filter-container">
            <div class="filter-name">{{ __('dialect') }}</div>
            <select name="dialect" onchange="this.form.submit()">
                <option value=""></option>
                @foreach ($dialects as $dialect)
                    <option value="{{ $dialect->id }}" {{ request('dialect') == $dialect->id ? 'selected' : '' }}>
                        {{ $dialect->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-container">
            <div class="filter-name">{{ __('location') }}</div>
            <select name="location" onchange="this.form.submit()">
                <option value=""></option>
                @foreach ($locations as $location)
                    <option
                        value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                        {{ $location->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-container">
            <div class="filter-name">{{ __('gender') }}</div>
            <select name="gender" onchange="this.form.submit()">
                <option value=""></option>
                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                <option
                    value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
            </select>
        </div>

        <div class="filter-container">
            <div class="filter-name">{{ __('sort') }}</div>
            <select name="sort" onchange="this.form.submit()">
                <option
                    value="latest" {{ $currentSort === 'latest' ? 'selected' : '' }}>{{ __('by Latest') }}</option>
                <option
                    value="fluency" {{ $currentSort === 'fluency' ? 'selected' : '' }}>{{ __('by Fluency') }}</option>
            </select>
        </div>
    </form>

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
                        {{ request('location') ? 'from '.\App\Models\Location::find(request('location'))->name : '' }}
                        in the Audio Library.
                    </p>
                </x-tip>
            @else
                <x-tip>
                    <p>Displaying all {{ number_format($totalCount) }} Audios.</p>
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
