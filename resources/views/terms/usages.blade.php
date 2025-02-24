@extends ('layouts.main')

@section('content')

    <x-page-head>
        <x-link :href="route('sentences.index')">{{ __('phrasebook') }}</x-link>
        <x-link :href="route('terms.usages', $term)">{{ __('usages') }}</x-link>
    </x-page-head>

    <div class="term-container">
        <div class="term-container-head">
            <div class="term-headword">
                <div class="term-headword-arb">{{ $term->term }}</div>
                <div class="term-headword-eng">({{ $term->translit }})</div>
            </div>
        </div>
        @foreach ($term->glosses as $gloss)

            <h1>{{ $gloss->gloss }}</h1>

            <div class="sentences-list">
                @foreach ($gloss->term->sentences($gloss->id)->get() as $sentence)
                    <x-sentence-item size="s" :sentence="$sentence"/>
                @endforeach
            </div>
        @endforeach
    </div>

@endsection
