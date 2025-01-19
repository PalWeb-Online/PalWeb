@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <a href="{{ route('signin') }}" class="feature-callout">{{ __('signup.already') }}</a>
        <div class="hero-title">Access Denied</div>
        <div class="hero-blurb">You need to sign in to perform this action or view this page.</div>
    </div>
@endsection

@section('content')
    <div class="tiers-container">
        <x-subscription-tier tier="guest"/>
        <x-subscription-tier tier="hobbyist"/>
        <x-subscription-tier tier="student"/>
    </div>
@endsection
