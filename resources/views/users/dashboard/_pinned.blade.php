<form method="GET" action="{{ route('dashboard.workbench') }}" class="workbench-options">
    <button name="sort" value="oldest" class="{{ request()->input('sort') == 'newest' ? '' : 'active' }}">Oldest First</button>
    <button name="sort" value="newest" class="{{ request()->input('sort') == 'newest' ? 'active' : '' }}">Newest First</button>
</form>

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
