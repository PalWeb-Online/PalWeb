@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 60">
            <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle">{{ __('dash.workbench') }}</text>
        </svg>
    </div>
@endsection

@section('content')
    <div class="workbench-portal-grid">
        <a class="workbench-portal-item" href="{{ route('decks.create') }}">
            <div>deckbuilderdeckbuilderdeckbuilder</div>
            <div>Deck Builder</div>
        </a>
        <a class="workbench-portal-item" href="{{ route('flashcards.study') }}">
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
