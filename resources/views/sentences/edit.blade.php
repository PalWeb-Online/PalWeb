@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <h1>{{ __('phrasebook') }}</h1>
    </div>
@endsection

@section('content')

    <form class="splash-panel" action="{{ route('sentences.update', $sentence) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="sp-head">
            <div>{{ __('sentences.edit') }}</div>
        </div>

        <x-auth-validation-errors :errors="$errors"/>

        <div class="field-wrapper">
            <div class="form-field">
                <label for="sentence[sentence]">Sentence *</label>
                <input type="text" id="sentence[sentence]"
                       name="sentence[sentence]"
                       value="{{ $sentence->sentence }}" required/>
            </div>
            <div class="form-field">
                <label for="sentence[translit]">Translit *</label>
                <input type="text" id="sentence[translit]"
                       name="sentence[translit]"
                       value="{{ $sentence->translit }}" required/>
            </div>
            <div class="form-field">
                <label for="sentence[trans]">Translat *</label>
                <input type="text" id="sentence[trans]"
                       name="sentence[trans]"
                       value="{{ $sentence->trans }}" required/>
            </div>
        </div>

        <button class="sp-button" type="submit">{{ __('submit') }}</button>
    </form>

@endsection
