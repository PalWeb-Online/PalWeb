@extends('layouts.main')

@section('content')
    <script>
        window.modelType = "{{ $modelType }}";
        window.stagedDialog = @json($dialog ?? null);
    </script>

    <div id="dialogger" class="app-container">
        <dialogger/>
    </div>
@endsection
