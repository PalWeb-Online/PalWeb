@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <h1>{{ __('deck-builder') }}</h1>
    </div>
@endsection

@section('content')
    <div class="splash-panel">
        <div class="sp-head">
            <div>{{ __('decks.edit') }}</div>
        </div>

        <div id="deckBuilder">
            <deck-builder mode="edit" deck-id="{{ $deck->id }}"></deck-builder>
        </div>
    </div>

@endsection
