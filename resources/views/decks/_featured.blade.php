<div class="decks-featured">
    <div class="deck-flashcard-grid">
        {{--        <div class="featured-title s">Featured</div>--}}
        <x-vue.deck component="DeckFlashcard" :deck="$featuredDeck" />
    </div>
    <div class="decks-list">
        <div class="featured-title m" style="text-transform: none">Newest</div>
        @foreach($newest as $deck)
            <x-vue.deck component="DeckItem" :deck="$deck" size="s"/>
        @endforeach
    </div>
    @if (count($mostSaved) > 0)
        <div class="decks-list popular">
            <div class="featured-title l" style="text-transform: none">Popular</div>
            @foreach($mostSaved as $deck)
                <x-vue.deck component="DeckItem" :deck="$deck" size="l"/>
            @endforeach
        </div>
    @endif
</div>
