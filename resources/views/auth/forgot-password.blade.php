@extends ('layouts.main')

@section('content')

    <form class="splash-panel" method="POST" action="{{ route('password.email') }}">
        @csrf

        <x-auth-session-status :status="session('status')"/>

        <div class="sp-head">
            <div>{{ __('password.reset') }}</div>
            <p>{{ __('password.forgot.prompt') }}</p>
        </div>

        <x-form-field name="email" type="email" :value="old('email')"
                      required autofocus maxlength="255"
                      :errors="$errors"/>

        <x-button class="sp-button">
            {{ __('password.send.link') }}
        </x-button>
    </form>

@endsection
