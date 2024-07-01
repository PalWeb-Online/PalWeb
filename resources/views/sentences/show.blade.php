@extends ('layouts.main')

@section('content')

    <x-page-head>
        <x-link :href="route('sentences.index')">{{ __('phrasebook') }}</x-link>
        <x-link :href="route('sentences.show', $sentence)">{{ __('view') }}</x-link>
    </x-page-head>

    <x-sentence size="l" :sentence="$sentence"/>

    <x-vocabulary>
        @foreach($sentence->getTerms() as $term)
            @if (is_array($term))
                <x-term/>
            @else
                <x-term :term="$term"/>
            @endif
        @endforeach
    </x-vocabulary>

@endsection
