@if($deck)
    <div class="deck-container">
        <x-vue.deck component="DeckHead" :deck="$deck" />

        <div class="user-wrapper">
            <div class="user-avatar">
                <img src="{{ asset('img/avatars/' . $deck->author->avatar) }}" alt="Profile Picture"/>
            </div>
            <div class="user-comment">
                <div class="user-comment-head">
                    <div>{{ $deck->author->name }}</div>
                    <div>({{ $deck->author->username }})</div>
                </div>
                <div class="user-comment-body">
                    <div class="user-comment-body-content">
                        @if($deck->description)
                            {{ $deck->description }}
                        @else
                            <i>Sadly, {{ $deck->author->name }} hasn't told us anything about this Deck yet.</i>
                        @endif
                    </div>
                    <div class="user-comment-body-data">Created by {{ $deck->author->name }}
                        on {{ $deck->created_at->format('j F Y') }}.
                    </div>
                </div>
            </div>
        </div>

        @if(count($deck->terms) > 0)
            <x-vocabulary>
                @foreach($deck->terms as $term)
                    <x-vue.term component="TermItem" :term="$term" :gloss="\App\Models\Gloss::find($term->pivot->gloss_id)"/>
                @endforeach
            </x-vocabulary>
        @else
            <x-tip>
                <p>This Deck is still empty! If this Deck is yours, use the menu in the top-right corner of this page to
                    <a
                        href="{{ route('decks.edit', $deck->id) }}">Edit the Deck</a>, or hover over the Context Actions
                    menu of a term & select the "Add to Deck" option to view a list of your Decks that you can add the
                    term
                    to.</p>
            </x-tip>
        @endif

        <div class="deck-term-count">{{ count($deck->terms) }} Terms</div>
    </div>
@endif
