@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <h1>{{ __('dash.subscription') }}</h1>
    </div>
@endsection

@section('content')
    <div class="tiers-container">
        <x-subscription-tier tier="guest"/>
        <x-subscription-tier tier="hobbyist"/>
        <x-subscription-tier tier="student"/>
    </div>
@endsection
