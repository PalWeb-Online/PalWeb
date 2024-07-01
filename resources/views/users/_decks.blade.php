<div class="decks-list">
    <div class="featured-title l">{{ __('decks') }}: {{ count($decks) }}</div>
    
    @if(count($decks) > 0)
        @foreach($decks as $deck)
            <x-deck-li :deck="$deck"/>
        @endforeach
    @else
        <x-tip>
            <p>Seems like they haven't created any Public Decks yet.</p>
        </x-tip>
    @endif
</div>

