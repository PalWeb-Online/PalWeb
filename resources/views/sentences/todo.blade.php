@extends ('layouts.main')

@section('content')

    <x-page-head>
        <x-link :href="route('sentences.index')">{{ __('phrasebook') }}</x-link>
        <x-link :href="route('missing.sentences.index')">{{ __('todo') }}</x-link>
    </x-page-head>

    <x-tip>
        <p>Only the first 100 items are shown in this list.</p>
    </x-tip>

    <div class="doc-section">
        <h1>Terms Missing Sentences</h1>
        <ul>
            @foreach ($terms as $term)
                <li><a href="{{ route('terms.show', $term) }}">{{ $term->term }}</a> ({{ $term->translit }}) "{{ $term->gloss }}"</li>
            @endforeach
        </ul>
    </div>

@endsection
