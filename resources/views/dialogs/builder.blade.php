@extends('layouts.main')

@section('content')
    <script>
        window.modelType = "{{ $modelType }}";
        window.modelId = @json($modelId ?? null);
    </script>

    <div id="dialogger" class="app-container">
        <dialogger/>
    </div>
@endsection
