@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <h1>{{ __('dash.account') }}</h1>
    </div>
@endsection

@section('content')

    <form class="splash-panel" method="POST" action="{{ route("settings.password.update") }}">
        @method('PATCH')
        @csrf

        <div class="sp-head">
            <div>{{ auth()->user()->password ? __('password.change') : __('password.set') }}</div>
        </div>

        <x-auth-validation-errors :errors="$errors"/>
        
        <x-form-field name="password_new" type="password"
                      required autocomplete="new-password" :errors="$errors" error="password"/>

        <x-form-field name="password_new_confirmation" type="password"
                      required :errors="$errors" error="password"/>

        <x-button class="sp-button">
            {{ __('submit') }}
        </x-button>
    </form>

@endsection
