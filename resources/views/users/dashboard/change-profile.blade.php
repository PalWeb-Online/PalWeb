@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <h1>{{ __('dash.account') }}</h1>
    </div>
@endsection

@section('content')

    <form class="splash-panel" method="POST" action="{{ route("settings.profile.update") }}">
        @method('PATCH')
        @csrf

        <div class="sp-head">
            <div>{{ __('profile.change') }}</div>
        </div>

        <x-auth-validation-errors :errors="$errors"/>

        <x-form-field name="name" :value="$user->name"
                      required autofocus maxlength="50" :errors="$errors"/>

        <x-form-field name="username" :value="$user->username"
                      required maxlength="50" :errors="$errors"/>

        <x-form-field name="ar_name" :value="$user->ar_name"
                      required maxlength="50" :errors="$errors"/>

        <button type="button" id="name-generator" class="material-symbols-rounded">
            ifl
        </button>

        <x-form-field name="home" :value="$user->home"
                      maxlength="100" :errors="$errors"/>

        <x-form-field textarea
                      name="bio" :value="$user->bio"
                      maxlength="500" :errors="$errors"/>

        <x-form-field select
                      name="dialect" :errors="$errors">
            <option value="1" {{ $user->dialect_id == '1' ? 'selected' : '' }}>{{ __('dialects.general') }}</option>
            <option value="2" {{ $user->dialect_id == '2' ? 'selected' : '' }}>{{ __('dialects.urban') }}</option>
            <option value="3" {{ $user->dialect_id == '3' ? 'selected' : '' }}>{{ __('dialects.rural') }}</option>
            <option value="4" {{ $user->dialect_id == '4' ? 'selected' : '' }}>{{ __('dialects.central') }}</option>
            <option
                value="5" {{ $user->dialect_id == '5' ? 'selected' : '' }}>{{ __('dialects.northern') }}</option>
            <option value="6" {{ $user->dialect_id == '6' ? 'selected' : '' }}>{{ __('dialects.bedouin') }}</option>
            <option value="7" {{ $user->dialect_id == '7' ? 'selected' : '' }}>{{ __('dialects.druze') }}</option>
        </x-form-field>

        <x-button class="sp-button">
            {{ __('dash.submit') }}
        </x-button>

    </form>

@endsection
