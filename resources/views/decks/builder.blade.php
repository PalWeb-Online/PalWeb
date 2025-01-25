@extends('layouts.main')

@section('content')
    <script>
        window.user = @json($user ?? null);
        window.stagedDeck = @json($deck ?? null);
    </script>

    <div id="deckBuilder" class="app-container">
        <deck-builder/>
    </div>
@endsection
