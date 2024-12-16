@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <a href="{{ route('terms.random') }}" class="feature-callout">I'm Feeling Lucky! -></a>
        <h1>{{ __('dictionary') }}</h1>
    </div>
@endsection

@section('content')

    <div id="dictionaryFilters">
        <dictionary-filters></dictionary-filters>
    </div>

    @if (! (bool) request()->query())
        <div class="portal-button-wrapper">
            <div class="portal-button-head">
                Power up your Arabic vocabulary!
            </div>
            <div class="portal-button-body">
                <a href="{{ route('explore.index') }}" class="portal-button">Explore Portal</a>
                <a href="{{ route('sentences.index') }}" class="portal-button">Phrasebook</a>
                <a href="{{ route('wiki.show', 'dictionary') }}" class="portal-button">User Manual</a>
            </div>
        </div>

        @include("terms._featured")
    @endif

    @if($totalCount > 0)
        {{--        using decks-list here just because the usual terms-list is for term-li's --}}
        <div class="decks-list">
            <div class="featured-title l">{{ __('all') }}</div>
            @if(collect($filter)->filter()->isNotEmpty())
                <x-tip>
                    <p>
                        Displaying {{ number_format($totalCount) }} {!! $filter['attribute'] ? '<b>'.ucwords($filter['attribute']).'</b>' : '' !!} {!! $filter['form'] ? '<b>Form '.ucwords($filter['form']).'</b>' : '' !!} {!! $filter['category'] ? '<b>'.ucwords($filter['category']).'s</b>' : 'terms' !!}{!! $filter['singular'] ? ' in the <b>'.$filter['singular'].'</b> pattern' : '' !!}{!! $filter['plural'] ? ' with a <b>'.$filter['plural'].'</b> plural' : '' !!}
                        matching this query.</p>
                    <p>Check the Dictionary <a href="{{ route('wiki.show', 'dictionary') }}">User Manual</a> to
                        ensure you get the results you're looking for.</p>
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
            <p>Check the Dictionary <a href="{{ route('wiki.show', 'dictionary') }}">User Manual</a> to ensure you get
                the results you're looking for.</p>
            <p>Is a term missing from the Dictionary? <a href="{{ route('terms.request') }}" target="_blank">Request</a>
                it here!</p>
        </x-tip>
    @endif

    {{ $terms->links() }}

@endsection
