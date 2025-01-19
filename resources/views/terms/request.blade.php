@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <h1>{{ __('dictionary') }}</h1>
    </div>
@endsection

@section('content')
    <form class="splash-panel" method="POST" action="{{ route("missing.terms.store") }}">
        @csrf

        <div class="sp-head">
            <div>{{ __('missing.terms.create') }}</div>
            <p>Use this form to request the addition of a term to the Dictionary. You may write the term any way you
                please: in Arabic, in English, etc. Don't worry about spelling, as long as the editor can identify
                the intended term. Indicate the term's category, if you know it.</p>
        </div>

        <x-form-field name="translit" required autofocus :errors="$errors"/>

        <x-form-field select
                      name="category" :errors="$errors">
            <option value="" selected></option>
            <option value="verb">verb</option>
            <option value="noun">noun</option>
            <option value="adjective">adjective</option>
            <option value="numeral">numeral</option>
            <option value="adverb">adverb</option>
            <option value="preposition">preposition</option>
            <option value="conjunction">conjunction</option>
            <option value="determiner">determiner</option>
            <option value="particle">particle</option>
            <option value="phrase">phrase</option>
        </x-form-field>

        <x-button class="sp-button">
            {{ __('dash.submit') }}
        </x-button>
    </form>

@endsection
