@extends('layouts.main')

@section('content')
    <script>
        window.action = "{{ $action ?? 'create' }}";
        window.stagedSentence = @json($sentence ?? null);
    </script>

    <div id="sentenceBuilder" class="app-container">
        <sentence-builder/>
    </div>
@endsection
