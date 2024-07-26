@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel" class="with-feature">
        <h1>{{ __('deck-builder') }}</h1>
    </div>
@endsection

@section('content')

    <div class="splash-panel">
        <div class="sp-head">
            <div>{{ __('decks.new') }}</div>
            <p>Welcome to the <b>Deck Builder</b>! Use this form to create a new <b>Deck</b>. By creating a <b>Deck</b>,
                you'll be able to group terms from the Dictionary in any way you want — & share these lists with others!
                In future updates, additional functionality will be added that will allow you to study these terms as
                Flashcards. <a href="https://youtu.be/R26-A9dgxCY" target="_blank">Watch the tutorial to get
                    started!</a>
            </p>
        </div>

        <div id="deckBuilder">
            <deck-builder mode="add"></deck-builder>
        </div>
    </div>

@endsection
