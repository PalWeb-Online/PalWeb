@extends ('layouts.main')

@section('content')
    <div class="flashcard-portal-head">
        <a href="{{ route('dashboard.workbench') }}">Back to Workbench</a>
        <x-vue.deck component="DeckHead" :deck="$deck"/>
    </div>

    <div id="flashcardPortal" style="min-width: 0">
        <flashcard-portal deck-id="{{ $deck->id }}"/>
    </div>
@endsection
