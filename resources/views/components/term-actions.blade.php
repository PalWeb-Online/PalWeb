@unless(request()->routeIs('terms.show'))
    <a href="{{ route('terms.show', $term) }}" target="_blank">View Term</a>
@endunless

@unless(request()->routeIs('terms.usages'))
    <a href="{{ route('terms.usages', $term) }}">View Usages</a>
@endunless

@auth
    @if(auth()->user()->isAdmin())
        <div class="action-divider"></div>
        <a href="{{ route('terms.edit', $term) }}">Edit
            Term</a>
        <form method="POST" action="{{ route('terms.destroy', $term) }}">
            @method('DELETE')
            @csrf
            <button onclick="return confirm('Are you sure you want to delete this term?')">
                Delete Term
            </button>
        </form>
    @endif

    <div class="action-divider"></div>
    <div data-vue-component="PinButton"
         data-props="{{ json_encode(['id' => $term->id, 'isPinned' => $term->isPinned()]) }}"></div>

    @php
        $userDecks = auth()->user()->decks->where('user_id', auth()->user()->id);
        $decksWithoutTerm = $userDecks->filter(function ($deck) use ($term) {
            return !$deck->terms->contains($term->id);
        });
        $decksWithTerm = $userDecks->filter(function ($deck) use ($term) {
            return $deck->terms->contains($term->id);
        });
    @endphp

    <div class="action-divider"></div>
    @if($decksWithoutTerm->isNotEmpty())
        <x-dropdown class="context-submenu">
            <x-slot name="trigger">
                <a>Add to Deck</a>
            </x-slot>
            <x-slot name="content">
                @foreach($decksWithoutTerm as $deck)
                    <form method="POST"
                          action="{{ route('decks.term.toggle', ['deck' => $deck->id, 'term' => $term->id]) }}">
                        @csrf
                        <button type="submit">{{ $deck->name }}</button>
                    </form>
                @endforeach
            </x-slot>
        </x-dropdown>
    @endif
    @if($decksWithTerm->isNotEmpty())
        <x-dropdown class="context-submenu">
            <x-slot name="trigger">
                <a>Remove from Deck</a>
            </x-slot>
            <x-slot name="content">
                @foreach($decksWithTerm as $deck)
                    <form method="POST"
                          action="{{ route('decks.term.toggle', ['deck' => $deck->id, 'term' => $term->id]) }}">
                        @csrf
                        <button type="submit">{{ $deck->name }}</button>
                    </form>
                @endforeach
            </x-slot>
        </x-dropdown>
    @endif
@endauth
