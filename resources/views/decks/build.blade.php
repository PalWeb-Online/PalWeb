@extends('layouts.main')

@section('content')
    <script>
        window.user = @json($user ?? null);
        window.deck = @json($deck ?? null);
        window.action = "{{ $action ?? 'create' }}";
    </script>

    <div id="deckBuilder" class="app-container">
        <deck-builder/>
    </div>
@endsection
