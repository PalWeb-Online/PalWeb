@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel">
        <h1>{{ __('phrasebook') }}</h1>
        <div class="hero-blurb">Find all uses of a term to understand it in context!</div>
        <x-search-bar route="sentences.index"/>
    </div>
@endsection

@section('content')
    @if($totalCount > 0)
        <div class="sentence-list">
            <div class="featured-title l">{{ __('all') }}</div>

            @if(request()->query('search'))
                <x-tip>
                    <p>Displaying {{ number_format($totalCount) }} sentences matching this query.</p>
                    <p>Check the Dictionary <a href="{{ route('wiki.show', 'dictionary') }}">User Manual</a> to ensure
                        you
                        get the results
                        you're looking for.</p>
                </x-tip>
            @else
                <x-tip>
                    <p>Displaying all {{ number_format($totalCount) }} sentences in the Dictionary.</p>
                </x-tip>
            @endif
            @foreach($sentences as $sentence)
                <x-vue.sentence component="SentenceItem" size="s" :sentence="$sentence"/>
            @endforeach
        </div>
    @else

        <x-tip>
            <p>No results matching this query.</p>
            <p>Check the Dictionary <a href="{{ route('wiki.show', 'dictionary') }}">User Manual</a> to ensure you get
                the results you're looking for.</p>
        </x-tip>
    @endif

    {{ $sentences->links() }}

@endsection
