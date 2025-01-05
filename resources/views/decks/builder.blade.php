@extends('layouts.main')

@section('content')
    <script>
        window.action = "{{ $action ?? 'create' }}";
        window.user = @json($user ?? null);
        window.stagedDeck = @json($deck ?? null);
    </script>

    <div id="deckBuilder" class="app-container">
        <deck-builder/>
    </div>
@endsection
