<div class="decks-list">
    <div class="featured-title l">{{ __('decks') }}: {{ count($decks) }}</div>

    @if(count($decks) > 0)
        @foreach($decks as $deck)
            <x-vue.deck component="DeckItem" :deck="$deck"/>
        @endforeach
    @else
        <x-tip>
            <p>{{ $user->name }} has not created any Public Decks yet.</p>
        </x-tip>
    @endif
</div>

