<form method="GET" action="{{ route('dashboard.workbench') }}">
    <button name="sort" value="oldest">Oldest First</button>
    <button name="sort" value="newest">Newest First</button>
</form>

<div class="featured-title l">Decks: {{ count($decks) }}</div>
<x-tip>
    <div>Ready to study your Flashcard Decks? Click on any Deck you've pinned & select <b>Study Deck</b>!</div>
</x-tip>
@if(count($decks) > 0)
    <div class="deck-flashcard-grid">
        @foreach($decks as $deck)
            <x-vue.deck component="DeckFlashcard" :deck="$deck"/>
        @endforeach
    </div>
@else
    <x-tip>
        <p>Seems like you haven't pinned any Decks yet; for now, this section is empty & sad. Go to the <a
                href="{{ route('decks.index') }}">Deck Library</a> to view & pin Decks that other Palestinian Arabic
            learners have been creating!</p>
    </x-tip>
@endif


<div class="terms-list">
    <div class="featured-title l">Terms: {{ App\Models\Term::whereHasBookmark(auth()->user())->count() }}</div>
    @if(count($terms) > 0)
        @foreach($terms as $term)
            <x-term-item :term="$term"/>
        @endforeach
    @else
        <x-tip>
            <p>Seems like you haven't pinned any Terms yet; for now, this section is empty & sad. Head over
                to the
                <a href="/dictionary">Dictionary</a> or the <a href="/lessons">Curriculum</a> to get started
                learning
                Palestinian Arabic!</p>
        </x-tip>
    @endif
</div>


<div class="sentence-list">
    <div class="featured-title l">Sentences: {{ App\Models\Sentence::whereHasBookmark(auth()->user())->count() }}</div>
    @if(count($sentences) > 0)
        @foreach($sentences as $sentence)
            <x-sentence-item size="s" :sentence="$sentence"/>
        @endforeach

    @else
        <x-tip>
            <p>Seems like you haven't pinned any Sentences yet; for now, this section is empty & sad. Head over
                to the <a href="{{ route('sentences.index') }}">Sentence Library</a> to get started learning Palestinian
                Arabic!
            </p>
        </x-tip>
    @endif
</div>
