@props([
    'component' => $component,
    'deck' => $deck,
    'size' => 'm'
])

@if($deck)
    @php
        $deckObject = [
            'name' => $deck->name,
            'description' => $deck->description,
            'count' => count($deck->terms),
            'pinCount' => \Maize\Markable\Models\Bookmark::count($deck),
            'isPrivate' => $deck->private,
            'authorName' => $deck->author->name,
            'authorAvatar' => asset('img/avatars/' . $deck->author->avatar),
            'terms' => $deck->terms->pluck('term'),
            'size' => $size
        ];
    @endphp

    <div data-vue-component="{{ $component }}"
         data-props="{{ json_encode([
                 'deck' => $deckObject,
                 'imageURL' => asset('/img'),
                 'isPinned' => $deck->isPinned(),

                 'routes' => [
                     'view' => route('decks.show', $deck),
                     'edit' => route('decks.edit', $deck),
                     'delete' => route('decks.destroy', $deck),
                     'study' => route('flashcards.study', $deck),
                     'creator' => route('users.show', $deck->author->username),
                     'pin' => route('decks.pin', $deck),
                     'privacyToggle' => route('decks.privacy.toggle', $deck),
                     'copy' => route('decks.copy', $deck),
                     'export' => route('decks.export', $deck)
                 ],
                 'isUser' => auth()->check(),
                 'isAuthor' => auth()->check() && $deck->author->id == auth()->user()->id,
             ]) }}"
    >
    </div>
@endif
