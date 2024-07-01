@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <a href="{{ route('signin') }}" class="feature-callout">{{ __('signup.already') }}</a>
        <h1>{{ __('signup') }}</h1>
    </div>
@endsection

@section('content')

    <form class="splash-panel" method="POST" action="{{ route('signup') }}">
        @csrf

        <x-form-field name="name" :value="old('name')"
                      required autofocus maxlength="50"
                      :errors="$errors"/>

        <x-form-field name="username" :value="old('username')"
                      required maxlength="50"
                      :errors="$errors"/>

        <x-form-field name="ar_name" :value="old('ar_name')"
                      required maxlength="50"
                      :errors="$errors"/>
        <button type="button" id="name-generator" class="material-symbols-rounded">
            ifl
        </button>

        <x-form-field name="email" type="email" :value="old('email')"
                      required maxlength="255"
                      :errors="$errors"/>

        <x-form-field name="password" type="password" required autocomplete="password"
                      :errors="$errors" error="password"/>

        <x-form-field name="password_confirmation" type="password" required
                      :errors="$errors" error="password"/>

        <x-button class="sp-button">
            {{ __('signup') }}
        </x-button>
    </form>

@endsection
