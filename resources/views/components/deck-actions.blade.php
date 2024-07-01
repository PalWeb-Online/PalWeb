@if($deck->author->id == auth()->user()->id)
    <a href="{{ route('decks.edit', $deck->id) }}">Edit Deck</a>
    <form method="POST" action="{{ route('decks.destroy', $deck->id) }}">
        @method('DELETE')
        @csrf
        <button onclick="return confirm('Are you sure you want to delete this Deck?')">
            Delete Deck
        </button>
    </form>
    <form method="POST" action="{{ route('decks.privacy.toggle', $deck->id) }}">
        @method('PATCH')
        @csrf
        <button>
            Make {{ $deck->private ? 'Public' : 'Private' }}
        </button>
    </form>
    <div class="action-divider"></div>
@endif

<a href="{{ route('users.show', $deck->author->username) }}">View Creator</a>
<div class="action-divider"></div>

<form method="POST" action="{{ route("decks.pin", ["deck" => $deck->id]) }}">
    @csrf
    <button onclick="this.closest('form').submit(); return false;">
        {{ $deck->isPinned() ? __('pin.remove') : __('pin.add') }} Deck
    </button>
</form>

<form method="POST" action="{{ route('decks.copy', $deck->id) }}">
    @csrf
    <button onclick="return confirm('Are you sure you want to create a copy of this Deck?')">Copy Deck
    </button>
</form>
<div class="action-divider"></div>

<a href="#" onclick="event.preventDefault(); copyToClipboard('{{ route('decks.show', $deck->id) }}')">Share
    Link</a>
<form method="POST" action="{{ route('decks.export', $deck->id) }}">
    @csrf
    <button onclick="return confirm('Are you sure you want to export this Deck?')">Export to CSV</button>
</form>
