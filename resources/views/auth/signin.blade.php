@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <a href="{{ route('signup') }}" class="feature-callout">{{ __('signup.prompt') }}</a>
        <h1>{{ __('signin') }}</h1>
        <a href="{{ route('auth.discord') }}" class="hero-button">{{ __('auth.discord') }}</a>
    </div>
@endsection

@section('content')

    <form class="splash-panel" method="POST" action="{{ route('signin') }}">
        @csrf

        <x-form-field name="email" type="email" :value="old('email')"
                      required autofocus maxlength="255"
                      :errors="$errors"/>

        <x-form-field name="password" type="password" required autocomplete="current-password"
                      :errors="$errors" error="email"/>

        <a class="sp-link" href="{{ route('password.request') }}">
            {{ __('password.forgot') }}
        </a>

        <label class="checkbox" for="remember_me">
            <input id="remember_me" type="checkbox" name="remember">
            <span>{{ __('auth.remember') }}</span>
        </label>

        <x-button class="sp-button">
            {{ __('signin') }}
        </x-button>
    </form>

@endsection
