@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 60">
            <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle">{{ __('dash.subscription') }}</text>
        </svg>
    </div>
@endsection

@section('content')
    <div class="tiers-container">
        <x-subscription-tier tier="guest"/>
        <x-subscription-tier tier="hobbyist"/>
        <x-subscription-tier tier="student"/>
    </div>
@endsection
