<x-page-head title="{{ __('syntax') }}"
             blurb="Learn about the sentence structure, parts of speech & clause types in Palestinian Arabic.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'syntax')">{{ __('syntax') }}</x-link>
</x-page-head>

<div class="doc-section">
    <h1>Sentence Structure</h1>
</div>

<div class="doc-section">
    <h1>Parts of Speech</h1>
    <p>Palestinian Arabic terms are grouped into ten categories based on their morphological & syntactic properties.
        While each category has a variety of characteristics in terms of their usage, they may be defined by one or two
        distinct features. Navigate to each page to read a detailed description of their features.</p>

    <ol>
        <li>
            <x-link :href="route('wiki.show', 'verbs')">{{ ucwords(__('verbs')) }}</x-link>
            are subject to person agreement with the sentence
            subject. They have tense & mood. Every sentence has one.
        </li>
        <li>
            <x-link :href="route('wiki.show', 'nouns')">{{ ucwords(__('nouns')) }}</x-link>
            have grammatical gender, number & definiteness.
        </li>
        <li>
            <x-link :href="route('wiki.show', 'adjectives')">{{ ucwords(__('adjectives')) }}</x-link>
            are modifiers that have a noun as their
            referent & are subject to gender, number & definiteness agreement with it.
        </li>
        <li>
            <x-link :href="route('wiki.show', 'numerals')">{{ ucwords(__('numerals')) }}</x-link>
            are adjectives that can head a counting clause &
            are not necessarily subject to grammatical agreement.
        </li>
        <li>
            <x-link :href="route('wiki.show', 'adverbs')">{{ ucwords(__('adverbs')) }}</x-link>
            are modifiers that do not have a noun as their
            referent & hence are not subject to agreement.
        </li>
        <li>
            <x-link :href="route('wiki.show', 'prepositions')">{{ ucwords(__('prepositions')) }}</x-link>
            are non-nouns that are always the head of
            a genitive structure.
        </li>
        <li>
            <x-link :href="route('wiki.show', 'conjunctions')">{{ ucwords(__('conjunctions')) }}</x-link>
            link sentence elements to each other
            without interacting with them.
        </li>
        <li>
            <x-link :href="route('wiki.show', 'determiners')">{{ ucwords(__('determiners')) }}</x-link>
            precede a noun & define its reference
            point.
        </li>
        <li>
            <x-link :href="route('wiki.show', 'particles')">{{ ucwords(__('particles')) }}</x-link>
            are functional terms that do not belong to any
            other part of speech.
        </li>
        <li>
            <x-link :href="route('wiki.show', 'phrases')">{{ ucwords(__('phrases')) }}</x-link>
            include greetings, interjections, discourse markers
            & similar terms that are generally
            syntax-independent.
        </li>
    </ol>
</div>

<div class="doc-section">
    <h1>Clause Types</h1>
</div>

{{--    <p>The Parts of Speech may be divided into two general types: <b>open categories</b> & <b>closed categories</b>,--}}
{{--        including <b>semi-closed categories</b>. While <b>open categories</b> are those that may include an in-principle--}}
{{--        infinite number of terms, <b>closed categories</b> have a finite number of terms. It should be noted that terms--}}
{{--        in <b>closed categories</b> may have a wide number of variants. Meanwhile, <b>semi-closed categories</b> do not--}}
{{--        accept new terms per se, but different terms may be used in different areas; for instance, <b>زيّ (zayy)</b> in--}}
{{--        some areas vs. <b>متل (mitl)</b> in others.</p>--}}


{{--    <ol>--}}
{{--        <li>حتّى</li>--}}
{{--        <li>إلي عليك</li>--}}
{{--    </ol>--}}
