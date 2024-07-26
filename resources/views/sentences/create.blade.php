@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <h1>{{ __('phrasebook') }}</h1>
    </div>
@endsection

@section('content')

    <div class="splash-panel">
        <div class="sp-head">
            <div>{{ __('sentences.new') }}</div>
        </div>
        <div id="sentenceEditor">
            <sentence-editor mode="add"></sentence-editor>
        </div>
    </div>

@endsection
