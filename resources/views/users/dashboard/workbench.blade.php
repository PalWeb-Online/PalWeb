@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <h1>{{ __('dash.workbench') }}</h1>
    </div>
@endsection

@section('content')

    <div class="workbench-portal-grid">
        <a class="workbench-portal-item" href="{{ route('decks.create') }}">
            <div>deckbuilderdeckbuilderdeckbuilder</div>
            <div>Deck Builder</div>
        </a>
        <a class="workbench-portal-item">
            <div>flashcardviewerflashcardviewerflashcardviewer</div>
            <div>Flashcard Viewer</div>
        </a>
        <a class="workbench-portal-item" href="{{ route('audios.record') }}">
            <div>recordwizardrecordwizardrecordwizard</div>
            <div>Record Wizard</div>
        </a>
    </div>

    @include('users.dashboard._pinned')

@endsection
