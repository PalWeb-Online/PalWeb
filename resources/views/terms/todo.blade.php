@extends ('layouts.main')

@section('content')

    <x-page-head>
        <x-link :href="route('terms.index')">{{ __('dictionary') }}</x-link>
        <x-link :href="route('terms.todo')">{{ __('todo') }}</x-link>
    </x-page-head>

    <div class="doc-section">
        <h1>From Sentences</h1>
        <div class="missing-terms">
            @foreach ($fromSentences as $term)
                <div>
                    {{ $term->sent_term }}
                    ({{ $term->sent_translit }})

                    <a href="{{ route('sentences.show', $term->sentence_id) }}">View Sentence</a>
                </div>
            @endforeach
        </div>

        <h1>Missing Terms</h1>
        <div class="missing-terms">
            @foreach ($missingTerms as $missingTerm)
                <div>
                    {{ $missingTerm->translit }}
                    ({{ $missingTerm->category }})

                    <form method="POST" action="{{ route('missing.destroy', $missingTerm) }}">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Are you sure you want to delete this item from the list?')">
                            <a>Delete Missing</a>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <h1>Missing Inflections</h1>
        <div class="missing-terms">
            @foreach ($missingInflections as $missingInflection)
                <div>
                    {{ $missingInflection->inflection }}
                    {{ $missingInflection->translit }}
                    ({{ $missingInflection->form }})

                    <form method="POST" action="{{ route('missing.destroy', $missingInflection) }}">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Are you sure you want to delete this sentence?')">
                            <img src="{{ asset('/img/trash.svg') }}" alt="Delete"/>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

@endsection
