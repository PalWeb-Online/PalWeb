@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel">
        <a href="{{ route('decks.create') }}" class="feature-callout">Build Your Own! -></a>
        <h1>{{ __('decks') }}</h1>
        <x-sg-trigger/>
    </div>
@endsection

@section('content')
    @if($totalCount > 0)
        <div class="decks-list">
            <div class="featured-title l">{{ __('all') }}</div>
            @if($hasFilters)
                <x-tip>
                    <p>Displaying {{ number_format($totalCount) }} Decks containing
                        {!! $filters['attribute'] ? '<b>'.ucwords($filters['attribute']).'</b>' : '' !!} {!! $filters['form'] ? '<b>Form '.ucwords($filters['form']).'</b>' : '' !!} {!! $filters['category'] ? '<b>'.ucwords($filters['category']).'s</b>' : 'Terms' !!}{!! $filters['singular'] ? ' in the <b>'.$filters['singular'].'</b> pattern' : '' !!}{!! $filters['plural'] ? ' with a <b>'.$filters['plural'].'</b> plural' : '' !!}{!! request()->query('search') ? ' or having a title matching <b>'.$filters['search'].'</b>' : '' !!}.
                    </p>
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

    {{ $decks->appends(request()->query())->links() }}

@endsection
