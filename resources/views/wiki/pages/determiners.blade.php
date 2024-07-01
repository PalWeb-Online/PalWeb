<x-page-head title="{{ __('determiners') }}"
             blurb="Determiners are terms that precede a noun, indicating its reference point.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'syntax')">{{ __('syntax') }}</x-link>
    <x-link :href="route('wiki.show', 'determiners')">{{ __('determiners') }}</x-link>
</x-page-head>

<p>All <b>demonstrative determiners</b> may be used as pronouns; alternatively, they may be shortened to <b>هـ</b>
    (ha-). Likewise, <b>أنو</b> (ʔanu) & <b>أني</b> (ʔani) may be used as pronouns, but not <b>أيّ</b> (ʔayy).</p>

<p><b>Determiners</b> indicate the reference point of the <b>Noun</b>. Unlike <b>Adjectives</b>, they are part
    of
    the noun within the greater noun phrase. <b>Determiners</b> are a closed category is excusively made up of
    the
    <b>Definite
        Article الـ (l- "the")</b> & the <b>Demonstrative Determiners</b>, which must be used in conjunction
    with
    the
    <b>Definite Article</b> & are the same as the <b>Demonstrative Pronouns</b> save that they may be used in
    their
    <b>clitic
        form</b> <b>هـ (ha-)</b>.</p>

<x-sentence eng="the chair">
    <x-sentence-term arb="الـ" eng="l-" :term="$terms->firstWhere('translit', 'l-')"/>
    <x-sentence-term arb="كرسي" eng="kursy" :term="$terms->firstWhere('translit', 'kursi')"/>
</x-sentence>
<x-sentence eng="this chair">
    <x-sentence-term arb="هـ" eng="ha-" :term="$terms->firstWhere('translit', 'ha-')"/>
    <x-sentence-term arb="ـالـ" eng="l-" :term="$terms->firstWhere('translit', 'l-')"/>
    <x-sentence-term arb="كرسي" eng="kursy" :term="$terms->firstWhere('translit', 'kursi')"/>
</x-sentence>

<x-deck :deck="\App\Models\Deck::find(33)"/>
