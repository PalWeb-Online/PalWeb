@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel">
        <a href="{{ route('terms.random') }}" class="feature-callout">I'm Feeling Lucky! -></a>
        <h1>{{ __('dictionary') }}</h1>
        <x-sg-trigger/>
    </div>
@endsection

@section('content')

    @if (!request()->query())
        @include("terms._featured")
    @endif

    @if($totalCount > 0)
        {{--        using decks-list here just because the usual terms-list is for term-li's --}}
        <div class="decks-list">
            <div class="featured-title l">{{ __('all') }}</div>
            @if($hasFilters)
                <x-tip>
                    <p>Displaying {{ number_format($totalCount) }}
                        {!! $filters['attribute'] ? '<b>'.ucwords($filters['attribute']).'</b>' : '' !!} {!! $filters['form'] ? '<b>Form '.ucwords($filters['form']).'</b>' : '' !!} {!! $filters['category'] ? '<b>'.ucwords($filters['category']).'s</b>' : 'terms' !!}{!! $filters['singular'] ? ' in the <b>'.$filters['singular'].'</b> pattern' : '' !!}{!! $filters['plural'] ? ' with a <b>'.$filters['plural'].'</b> plural' : '' !!}{!! request()->query('search') ? ' matching <b>'.$filters['search'].'</b>' : '' !!}.
                    </p>
                </x-tip>

            @else
                <x-tip>
                    <p>Displaying all {{ number_format($totalCount) }} terms in the Dictionary.</p>
                </x-tip>
            @endif

            <ul class="search-results">
                @foreach ($terms as $term)
                    <li>
                        <a class="search-result" href="{{  route('terms.show', $term) }}">
                            <div>
                                {{ $term->term }}
                            </div>
                            <div>
                                ({{ $term->translit }}) {{ $term->category }}.
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @else

        <x-tip>
            <p>No results matching this query.</p>
            <p>Is a term missing from the Dictionary? <a href="{{ route('missing.terms.create') }}" target="_blank">Request</a>
                it here!</p>
        </x-tip>
    @endif

    {{ $terms->appends(request()->query())->links() }}

@endsection
