@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <h1>{{ $user->ar_name }}</h1>
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
