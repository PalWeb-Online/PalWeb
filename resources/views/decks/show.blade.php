@extends ('layouts.main')

@section('content')
    <x-page-head>
        <x-link :href="route('decks.index')">{{ __('decks') }}</x-link>
        <x-link :href="route('decks.show', $deck)">{{ __('view') }}</x-link>
    </x-page-head>

    <x-deck-container :deck="$deck"/>
@endsection
