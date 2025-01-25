@extends('layouts.main')

@section('content')
    <script>
        window.modelType = "{{ $modelType }}";
        window.stagedSentence = @json($sentence ?? null);
    </script>

    <div id="dialogger" class="app-container">
        <dialogger/>
    </div>
@endsection
