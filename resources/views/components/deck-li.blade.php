@props([
    'deck' => $deck,
    'size' => 'm'
])

@if($deck)
    <div class="deck-li-wrapper {{ $size }}">
        <a href="{{ route('decks.show', $deck->id) }}" class="deck-li">
            <div class="deck-metadata">
                <div style="display: flex; gap: 0.8rem; align-items: center">
                    <div class="deck-title">
                        {{ $deck->name }}
                    </div>
                    <div class="deck-term-count">
                        ({{ count($deck->terms) }})
                    </div>
                </div>

                @if($size === 'l' && $deck->description)
                    <div class="deck-description">{{ \Illuminate\Support\Str::limit($deck->description, 190) }}</div>
                @endif
            </div>

            <div class="deck-author">
                @if($deck->private)
                    <img class="lock" src="{{ asset('img/lock.svg') }}" alt="lock"/>
                @endif
                <div class="deck-author-name">by {{ $deck->author->name }}</div>
                <div class="deck-author-avatar">
                    <img alt="Profile Picture" src="{{ asset('img/avatars/' . $deck->author->avatar) }}"/>
                </div>
            </div>
        </a>

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

        <x-context-actions>
            <x-deck-actions :deck="$deck"/>
        </x-context-actions>
    </div>
@endif
