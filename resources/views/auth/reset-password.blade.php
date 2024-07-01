@extends ('layouts.main')

@section('content')

    <form class="splash-panel" method="POST" action="{{ route('password.update') }}">
        @csrf

        <div class="sp-head">
            <div>{{ __('password.reset') }}</div>
            <p>{{ __('password.reset.prompt') }}</p>
        </div>

        <!-- Token Input -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-form-field name="email" type="email" :value="old('email', $request->email)"
                      required autofocus maxlength="255"
                      :errors="$errors"/>

        <x-form-field name="password_new" type="password" required autocomplete="new-password"
                      :errors="$errors" error="password"/>

        <x-form-field name="password_new_confirmation" type="password" required
                      :errors="$errors" error="password"/>

        <x-button class="sp-button">
            {{ __('submit') }}
        </x-button>
    </form>

@endsection
