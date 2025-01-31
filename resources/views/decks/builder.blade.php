@extends('layouts.main')

@section('content')
    <script>
        window.stagedDeck = @json($stagedDeck ?? null);
    </script>

    <div id="deckBuilder" class="app-container">
        <deck-builder/>
    </div>
@endsection
