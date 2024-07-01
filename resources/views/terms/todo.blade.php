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

    <a href="{{ route("terms.index") }}" class="material-symbols-rounded subtitle">arrow_back</a>
    <div class="maintitle">To-Do</div>

    <h1>Missing Terms</h1>
    <ul>
        @foreach ($missingTerms as $missingTerm)
            <li>{{ $missingTerm->translit }} ({{ $missingTerm->category }})</li>
        @endforeach
    </ul>

    <h1>Missing Audios</h1>
    <ul>
        @foreach ($pronunciationsMissingAudios as $pronunciation)
            <li>{{ $pronunciation }}</li>
        @endforeach
        @foreach ($inflectionsMissingAudios as $inflection)
            @if(!in_array($inflection->form, ['ap', 'pp', 'nv', 'isPatient', 'noPatient', 'hasObject']))
                <li>CENTRAL URBAN PALESTINIAN: {{ $inflection->translit }}</li>
            @else
                <li>CENTRAL URBAN PALESTINIAN: <a
                        href="/dictionary/verb/{{ $inflection->translit }}">{{ $inflection->translit }}</a></li>
            @endif
        @endforeach
    </ul>

@endsection
