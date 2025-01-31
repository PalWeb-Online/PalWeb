@extends('layouts.main')

@section('content')
    <script>
        window.modelType = "{{ $modelType }}";
        window.modelData = @json($modelData ?? null);
    </script>

    <div id="dialogger" class="app-container">
        <dialogger/>
    </div>
@endsection
