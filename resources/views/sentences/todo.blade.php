@extends ('layouts.main')

@section('content')

    <x-page-head>
        <x-link :href="route('sentences.index')">{{ __('phrasebook') }}</x-link>
        <x-link :href="route('sentences.todo')">{{ __('todo') }}</x-link>
    </x-page-head>

    <div class="doc-section">
        <h1>Terms With Eligible Sentences</h1>
        <ul>
            @foreach ($withEligibleSentences as $term)
                <li><a href="{{ route('terms.show', $term) }}">{{ $term->term }}</a> ({{ $term->translit }})</li>
            @endforeach
        </ul>

        <h1>Terms Missing Sentences</h1>
        <ul>
            @foreach ($termsMissingSentences as $term)
                <li><a href="{{ route('terms.show', $term) }}">{{ $term->term }}</a> ({{ $term->translit }}) "{{ $term->gloss }}"</li>
            @endforeach
        </ul>

        <h1>Orphan Sentences</h1>
        <ul>
            @foreach ($orphanSentences as $orphanSentence)
                <li>{{ $sentence->translit }}</li>
            @endforeach
        </ul>
    </div>

@endsection
