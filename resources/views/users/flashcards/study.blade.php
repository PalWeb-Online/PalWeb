@extends ('layouts.main')

@section('content')
    <style>
        #page-body {
            margin-block: 1.6rem;
        }
    </style>

    <div class="flashcard-portal-head">
        <div>
            <a href="{{ route('dashboard.workbench') }}">Return to Workbench</a>
            <a href="{{ route('decks.show', $deck) }}">View Deck</a>
        </div>
        <div>{{ $deck->name }}</div>
    </div>

    <div id="flashcardPortal" style="min-width: 0">
        <flashcard-portal deck-id="{{ $deck->id }}"/>
    </div>
@endsection
