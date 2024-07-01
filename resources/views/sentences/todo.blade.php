@extends ('layouts.main')

@section('content')

    <style>

        h1 {
            font-family: 'JetBrains Mono', monospace, 'Readex Pro';
            text-transform: uppercase;
            font-weight: 700;
            font-size: 2.4rem;
        }

        ul {
            font-size: 1.6rem;
            font-family: 'JetBrains Mono', monospace, 'Readex Pro';
            font-weight: 700;
            margin: 1.6rem 0 0;
        }

        #main a {
            color: var(--grn)
        }

        #main a:hover {
            color: var(--red)
        }

    </style>

    <a href="{{ route("sentences.index") }}" class="material-symbols-rounded subtitle">arrow_back</a>
    <div class="maintitle">To-Do</div>

    <h1>Terms Missing Sentences</h1>
    <ul>
        @foreach ($termsMissingSentences as $term)
            <li><a href="/dictionary/{{ $term->slug }}">{{ $term->term }}</a></li>
        @endforeach
    </ul>

    <h1>Sentences Missing Audios</h1>
    <ul>
        @foreach ($sentencesMissingAudios as $sentence)
            <li>SENTENCE: {{ $sentence->translit }}</li>
        @endforeach
    </ul>

    <h1>Orphan Sentences</h1>
    <ul>
        @foreach ($orphanSentences as $orphanSentence)
            <li>{{ $sentence->translit }}</li>
        @endforeach
    </ul>

@endsection
