@unless(request()->routeIs('sentences.show'))
    <a href="{{ route('sentences.show', $sentence) }}" target="_blank">View Sentence</a>
@endunless

@auth
    @if(auth()->user()->isAdmin())
        <a href="{{ route('sentences.edit', $sentence) }}">Edit Sentence</a>

        <form method="POST" action="{{ route('sentences.destroy', $sentence) }}">
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Are you sure you want to delete this sentence?')">
                Delete Sentence
            </button>
        </form>
        <div class="action-divider"></div>
    @endif

    <form method="POST" action="{{ route("sentences.pin", ["sentence" => $sentence->id]) }}">
        @csrf
        <button onclick="this.closest('form').submit(); return false;">
            {{ $sentence->isPinned() ? __('pin.remove') : __('pin.add') }} Sentence
        </button>
    </form>
@endauth
