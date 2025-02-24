@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel">
        <h1>{{ __('phrasebook') }}</h1>
        <x-sg-trigger/>
    </div>
@endsection

@section('content')
    @if($totalCount > 0)
        <div class="sentences-list">
            <div class="featured-title l">{{ __('all') }}</div>
            @if($hasFilters)
                <x-tip>
                    <p>Displaying {{ number_format($totalCount) }} Sentences containing
                        {!! $filters['attribute'] ? '<b>'.ucwords($filters['attribute']).'</b>' : '' !!} {!! $filters['form'] ? '<b>Form '.ucwords($filters['form']).'</b>' : '' !!} {!! $filters['category'] ? '<b>'.ucwords($filters['category']).'s</b>' : 'Terms' !!}{!! $filters['singular'] ? ' in the <b>'.$filters['singular'].'</b> pattern' : '' !!}{!! $filters['plural'] ? ' with a <b>'.$filters['plural'].'</b> plural' : '' !!}{!! request()->query('search') ? ' matching <b>'.$filters['search'].'</b>' : '' !!}.
                    </p>
                </x-tip>

            @else
                <x-tip>
                    <p>Displaying all {{ number_format($totalCount) }} Sentences in the Phrasebook.</p>
                </x-tip>
            @endif

            @foreach($sentences as $sentence)
                <x-vue.sentence component="SentenceItem" size="s" :sentence="$sentence"/>
            @endforeach
        </div>
    @else

        <x-tip>
            <p>No results matching this query.</p>
        </x-tip>
    @endif

    {{ $sentences->appends(request()->query())->links() }}

@endsection
