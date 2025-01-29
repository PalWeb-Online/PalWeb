@extends ('layouts.main')

@section('content')

    <x-page-head>
        <x-link :href="route('sentences.index')">{{ __('phrasebook') }}</x-link>
        <x-link :href="route('sentences.show', $sentence)">{{ __('view') }}</x-link>
    </x-page-head>

    <x-vue.sentence component="SentenceItem" size="l" :sentence="$sentence" dialog/>

    <x-vocabulary>
        @foreach($sentence->terms as $term)
            <x-term-item :term="\App\Models\Term::find($term->id)" :gloss="\App\Models\Gloss::find($term->pivot->gloss_id)"/>
        @endforeach
    </x-vocabulary>

@endsection
