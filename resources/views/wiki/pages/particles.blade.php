<x-page-head title="{{ __('particles') }}" blurb="Particles are functional terms like negators, quantifiers & complementizers, that do not belong to
    any other category.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'syntax')">{{ __('syntax') }}</x-link>
    <x-link :href="route('wiki.show', 'particles')">{{ __('particles') }}</x-link>
</x-page-head>

<p>I have classified <b>تبع</b> as a <b>particle</b>, as it does not decisively belong to any other part of speech;
    the <b>تبع</b> structure that it creates is unique in the language.</p>

<p>While its meaning is semantically close to English <b>"of"</b> — a <b>preposition</b> — its gendered forms suggest
    that it is not a <b>preposition</b>, which do not observe grammatical agreement.</p>

<div class="array">
    <div class="example-sentence">
        <div class="example-eng" style="text-transform: uppercase">
            of
        </div>
        <div class="example-arb"
             style="flex-flow: row-reverse wrap; justify-content: space-around; font-family: 'Vazirmatn'; font-weight: 700">
            <span style="flex-basis: 40%">تبع</span>
            <span style="flex-basis: 40%">تبعة</span>
            <span style="flex-basis: 40%">تبعون</span>
        </div>
    </div>
</div>

<p>Additionally, if it were the head of a prepositional phrase, the phrase should be preceded by <b>اللي</b> if
    modifying a definite noun — & the term only ever defines a definite noun.</p>

<x-sentence-item eng="the man's motorcycle">
    <x-sentence-term arb="الموتور" eng="l-mōtōr" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    <x-sentence-term arb="تبع" eng="tabaʕ" :term="$terms->firstWhere('translit', 'tabaʕ')"/>
    <x-sentence-term arb="الزلمة" eng="z-zalame" :term="$terms->firstWhere('translit', 'zalame')"/>
</x-sentence-item>

<x-sentence-item eng="the motorcycle that belongs to the man">
    <x-sentence-term arb="الموتور" eng="l-mōtōr" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    <x-sentence-term arb="اللي" eng="l-li" :term="$terms->firstWhere('translit', 'l-li')"/>
    <x-sentence-term arb="للزلمة" eng="laz-zalame" :term="$terms->firstWhere('translit', 'zalame')"/>
</x-sentence-item>

<p>On the basis of its genderedness alone, it should be either a noun — namely, a pronoun — with feminine & plural
    forms, or an adjective subject to gender & number
    agreement.</p>

<p>Normally, nouns can only modify other nouns via <b>iḍāfa</b>; in this structure, the modified term is overtly
    indefinite, while the final modifier is overtly definite:</p>

<x-sentence-item eng="the man's car">
    <x-sentence-term arb="سيّارة" eng="sayyārt" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    <x-sentence-term arb="الزلمة" eng="z-zalame" :term="$terms->firstWhere('translit', 'zalame')"/>
</x-sentence-item>

<p>However, in the <b>تبع</b> structure, the modified noun features the article, showing that there is no <b>iḍāfa</b>.
    Instead, the <b>تبع</b> structure is itself an <b>iḍāfa</b> structure in which <b>تبع</b> is the modified term; this
    is audible in the pronunciation of the feminine form.</p>

<x-sentence-item eng="the man's car">
    <x-sentence-term arb="السيّارة" eng="s-sayyāra" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    <x-sentence-term arb="تبعة" eng="tabʕt" :term="$terms->firstWhere('translit', 'tabaʕ')"/>
    <x-sentence-term arb="الزلمة" eng="z-zalame" :term="$terms->firstWhere('translit', 'zalame')"/>
</x-sentence-item>

<p>Because of these reasons, the <b>تبع</b> structure is best understood as an adjective that can form <b>iḍāfa</b>,
    which is ordinarily impossible; the term observes definiteness agreement with the
    head noun — which must be definite in the <b>تبع</b> structure — but displays that agreement via <b>iḍāfa</b>.
</p>

<div class="wiki-container">
    <h1>{{ __('inventory') }}</h1>
    <x-vocabulary title="{{ __('particles') }}">
        <x-term-item :term="$terms->firstWhere('translit', 'ya-')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'fīh')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'hayy')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'tabaʕ')"/>
    </x-vocabulary>
    <x-vocabulary title="{{ __('negators') }}">
        <x-term-item :term="$terms->firstWhere('translit', 'mā')"/>
        <x-term-item :term="$terms->firstWhere('translit', '-š')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'miš')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bala')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'balāš')"/>
    </x-vocabulary>
    <x-vocabulary title="{{ __('quantifiers') }}">
        <x-term-item :term="$terms->firstWhere('translit', 'kull')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'wala')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔakamm')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'nafs')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ġēr')"/>
    </x-vocabulary>
    <x-vocabulary title="{{ __('complementizers') }}">
        <x-term-item :term="$terms->firstWhere('translit', 'ʔinn-')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ma-')"/>
    </x-vocabulary>
</div>
