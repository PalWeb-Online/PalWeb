@extends ('layouts.main')

@section('page-hero')
    <div id="hero-panel">
        <h1>{{ __('community') }}</h1>
    </div>
@endsection

@section('content')

    <x-portal-section title="Decks" blurb="What everyone has been studying lately. How about you?" img="suit-spade.svg">
        <a href="{{ route('decks.index') }}" class="portal-button">Browse</a>
        <a href="{{ route('decks.create') }}" class="portal-button">Create</a>
    </x-portal-section>

    <div class="decks-featured">
        <div class="deck-flashcard-grid">
            <x-vue.deck component="DeckFlashcard" :deck="$featuredDeck"/>
        </div>
        <div class="decks-list">
            <div class="featured-title m" style="text-transform: none">Latest</div>
            @foreach($latestDecks as $deck)
                <x-vue.deck component="DeckItem" :deck="$deck" size="s"/>
            @endforeach
        </div>
        @if (count($popularDecks) > 0)
            <div class="decks-list popular">
                <div class="featured-title l" style="text-transform: none">Popular</div>
                @foreach($popularDecks as $deck)
                    <x-vue.deck component="DeckItem" :deck="$deck" size="l"/>
                @endforeach
            </div>
        @endif
    </div>

    <x-portal-section title="Audios" blurb="Hear it from the horse's mouth." img="suit-diamond.svg">
        <a href="{{ route('audios.index') }}" class="portal-button">Browse</a>
        <a href="{{ route('audios.record') }}" class="portal-button">Create</a>
    </x-portal-section>

    @if ($latestAudios->count() > 0)
        <div class="audios-list">
            <div class="featured-title m" style="text-transform: none">Latest</div>
            @foreach($latestAudios as $audio)
                <x-pronunciation-item :pronunciation="$audio->pronunciation" :audio="$audio"/>
            @endforeach
        </div>
    @endif

    <x-portal-section title="Pals" blurb="Shoutout to the most helpful & prolific Pals in the PalWeb Community!"
                      img="suit-heart.svg"/>

    <div class="user-portrait-array">
        @foreach($users as $user)
            <x-user-portrait :user="$user" creations/>
        @endforeach
    </div>
@endsection
