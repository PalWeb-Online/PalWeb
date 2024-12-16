@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 60">
            <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle">
                {{ __('profile') }}
            </text>
        </svg>
    </div>
@endsection

@section('content')

    @include('users._profile')

    @include('users._badges')

    @if($user->speaker)
        @include('users._speaker', ['speaker' => $user->speaker])
    @endif

    @include('users._decks')

@endsection
