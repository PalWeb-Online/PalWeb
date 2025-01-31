@extends ('layouts.main')

@section('content')

    <x-page-head>
        <x-link :href="route('sentences.index')">{{ __('phrasebook') }}</x-link>
        <x-link :href="route('sentences.show', $sentence)">{{ __('view') }}</x-link>
    </x-page-head>

    <x-vue.sentence component="SentenceItem" size="l" :sentence="$sentence" dialog/>

@endsection
