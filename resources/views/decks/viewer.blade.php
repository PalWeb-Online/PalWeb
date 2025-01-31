@extends('layouts.main')

@section('content')
    <script>
        window.pinnedDecks = @json($pinnedDecks ?? null);
    </script>

    <div id="cardViewer" class="app-container">
        <card-viewer/>
    </div>
@endsection
