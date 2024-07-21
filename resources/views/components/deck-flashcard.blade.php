@if($deck)
    <div class="deck-flashcard-wrapper">
        <a class="deck-flashcard" href="{{ route('decks.show', ['deck' => $deck->id]) }}">
            <div class="deck-flashcard-front">
                <img class="pin {{ $deck->isPinned() ? '' : 'disabled' }}"
                     src="{{ asset('img/pin.svg') }}" alt="pin"/>
                <div class="deck-flashcard-front-head">
                    <div class="deck-title">{{ $deck->name }}</div>
                    <div class="deck-author" style="align-self: flex-end">
                        @if($deck->author)
                            @if($deck->private)
                                <img class="lock" src="{{ asset('img/lock.svg') }}" alt="lock"/>
                            @endif
                            <div class="deck-author-name">by {{ $deck->author->name }}</div>
                            <img class="deck-author-avatar"
                                 src="{{ asset('img/avatars/' . $deck->author->avatar) }}" alt="Profile Picture"/>
                        @else
                            <div class="deck-author-name">by Deleted User</div>
                        @endif
                    </div>
                </div>
                <div class="deck-flashcard-front-body">
                    <div class="deck-description">{{ \Illuminate\Support\Str::limit($deck->description, 150) }}</div>
                </div>
            </div>
            <div class="deck-flashcard-back">
                <div class="deck-flashcard-back-head">{{ count($deck->terms) }} terms</div>
                <div class="deck-flashcard-back-body">
                    @foreach($deck->terms->take(16) as $term)
                        <div>{{ $term->term }}</div>
                    @endforeach
                    @if(count($deck->terms) > 16)
                        <div style="grid-column: span 2">...</div>
                    @endif
                </div>
            </div>
        </a>

        <x-context-actions>
            <x-deck-actions :deck="$deck"/>
        </x-context-actions>
    </div>
@endif
