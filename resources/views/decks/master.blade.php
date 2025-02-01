@extends('layouts.main')

@section('content')
    <script>
        window.stagedDeck = @json($stagedDeck ?? null);
        window.pinnedDecks = @json($pinnedDecks ?? null);
    </script>

    <div id="deckMaster" class="app-container">
        <deck-master/>
    </div>
@endsection
