@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel">
        <a href="{{ route('decks.create') }}" class="feature-callout">Build Your Own! -></a>
        <h1>{{ __('decks') }}</h1>
        <x-search-bar route="decks.index" />
    </div>
@endsection

@section('content')
    @if($totalCount > 0)
        <div class="decks-list">
            <div class="featured-title l">{{ __('all') }}</div>

            @if(request()->query('search'))
                <x-tip>
                    <p>Displaying {{ number_format($totalCount) }} Decks matching this query.</p>
                </x-tip>

            @else
                <x-tip>
                    <p>Displaying all {{ number_format($totalCount) }} Decks in the Deck Library.</p>
                </x-tip>
            @endif

            @foreach($decks as $deck)
                <x-vue.deck component="DeckItem" :deck="$deck"/>
            @endforeach
        </div>
    @else

        <x-tip>
            <p>No results matching this query.</p>
        </x-tip>
    @endif

    {{ $decks->links() }}

@endsection
