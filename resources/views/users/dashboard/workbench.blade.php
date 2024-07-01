@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel">
        <h1>{{ __('dash.workbench') }}</h1>
    </div>
@endsection

@section('content')

    @include('users.dashboard._pinned')

@endsection
