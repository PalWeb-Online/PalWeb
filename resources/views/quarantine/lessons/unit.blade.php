@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <h1>{{ __('unit') }} {{ $unit }}</h1>
        <div class="hero-title">{{ __('lessons.' . $unit) }}</div>
    </div>
@endsection

@section('content')

    @include("lessons.pages." . $unit, ["terms" => $terms])

@endsection
