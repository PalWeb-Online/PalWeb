@if($deck)
    <div class="deck-container">
        <div class="deck-container-head">
            <div class="deck-container-head-title">{{ $deck->name }}</div>

            @auth
                <x-context-actions>
                    @if (! Route::currentRouteNamed('decks.show'))
                        <a href="{{ route('decks.show', $deck->id) }}">View Deck</a>
                    @endif
                    <x-deck-actions :deck="$deck" :user="auth()->user()"/>
                </x-context-actions>
            @endauth
        </div>


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
                    <x-term :term="$term"/>
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

        @if($deck->isPinned())
            <img class="pin" src="{{ asset('img/pin.svg') }}" alt="pin"/>
        @endif

        @php
            $pinCount = \Maize\Markable\Models\Bookmark::count($deck);
        @endphp
        @if($pinCount > 1)
            <div class="pin-counter">
                <img src="{{ asset('img/heart.svg') }}" alt="heart"/>
                <div>{{ $pinCount }}</div>
            </div>
        @endif

        @if($deck->private)
            <img class="lock" src="{{ asset('img/lock.svg') }}" alt="lock"/>
        @endif
    </div>
@endif
