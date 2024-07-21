@extends ('layouts.main')

@section('content')

    <x-page-head>
        <x-link :href="route('terms.index')">{{ __('dictionary') }}</x-link>
        <x-link :href="route('terms.todo')">{{ __('todo') }}</x-link>
    </x-page-head>

    <div class="doc-section">
        <h1>Missing Terms</h1>
        <ul>
            @foreach ($missingTerms as $missingTerm)
                <li>{{ $missingTerm->translit }} ({{ $missingTerm->category }})</li>
            @endforeach
        </ul>

        <h1>Missing Inflections</h1>
        <ul>
            @foreach ($missingInflections as $missingInflection)
                <li>{{ $missingInflection->inflection }} {{ $missingInflection->translit }} ({{ $missingInflection->form }})</li>
            @endforeach
        </ul>
    </div>

@endsection
