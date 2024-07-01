@extends ('layouts.main')

@section('content')

    <div class="splash-panel">
        <div class="sp-head">
            <div>{{ __('verification') }}</div>
            <p>{{ __('verification.prompt') }}</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <p>{{ __('verification.sent') }}</p>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-button class="sp-link">
                {{ __('verification.send.link') }}
            </x-button>
        </form>

        <form method="POST" action="{{ route('signout') }}">
            @csrf

            <x-button class="sp-button">
                {{ __('signout') }}
            </x-button>
        </form>
    </div>

@endsection
