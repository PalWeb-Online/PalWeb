<x-page-head title="{{ __('conjunctions') }}" blurb="Conjunctions are terms that connect clauses, sentences, or words, coordinating their relationship in
    a sentence.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'syntax')">{{ __('syntax') }}</x-link>
    <x-link :href="route('wiki.show', 'conjunctions')">{{ __('conjunctions') }}</x-link>
</x-page-head>

<x-vue.deck component="DeckContainer" :deck="\App\Models\Deck::find(34)"/>
