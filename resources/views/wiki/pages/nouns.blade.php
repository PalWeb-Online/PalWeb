<x-page-head title="{{ __('nouns') }}" blurb="Nouns are terms that have grammatical gender & number, with the possibility of being definite or
    indefinite.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'syntax')">{{ __('syntax') }}</x-link>
    <x-link :href="route('wiki.show', 'nouns')">{{ __('nouns') }}</x-link>
</x-page-head>

<div class="doc-section">
    <h1>Syntax</h1>

    <h1>Agreement</h1>
    <p>In Arabic, there are four types of grammatical agreement: <b>gender</b>, <b>number</b>, <b>person</b> & <b>definiteness</b>.
        In Palestinian Arabic, <b>gender</b> & <b>number</b> are functionally a single combined category, as nouns are
        either <b>masculine</b>, <b>feminine</b>, or <b>plural</b>, with the latter always being gender-neutral & not
        necessarily a reflection of the semantic numerousness of the noun.</p>
    <p>Indeed, the syntactic categorization of a noun cannot be determined on the basis of its semantic gender & number
        alone — <b>animacy</b> plays a part as well — & in some cases multiple categorizations are possible, depending
        on
        context & intent. Additionally, certain grammatical structures instantiate specific types of agreement; the
        semantic characteristics of Arabic nouns are often disaggregated from their actual syntactic features.</p>
    <p>Nouns that are treated as <b>animate</b> — primarily humans — tend to reflect the ideal categorization of nouns,
        with <b>plural</b> <b>animate</b> nouns generally being grammatically <b>plural</b>. Still, certain nouns that
        refer to masses of <b>animate</b> nouns — like <b>ناس (nās "people")</b> — may be treated as grammatically
        <b>feminine</b> as well. Either form of agreement is correct, but <b>feminine</b> agreement treats the noun as
        a single undifferentiated mass, as opposed to the individuation indicated by <b>plural</b> agreement.</p>
    <p>As for non-<b>animate</b> nouns, they are never individuated by default, so a multiplicity of a certain noun is
        generally treated as a single undifferentiated mass; these masses, as well, are always <b>feminine</b> (or <b>mass</b>
        gender, if you will). Notice that <b>mass nouns</b> — <b>animate</b> or not — are always <b>feminine</b> by
        default, but <b>animate</b> ones always have the ability of being <b>plural</b> as well.</p>
    <p>In fact, non-<b>animate</b> nouns may be grammatically <b>plural</b> as well in certain circumstances:
        unsurprisingly, when they are not treated as masses, but as individuated instances of the noun. While there is
        no precise cutoff, counted quantities up to ten are usually grammatically <b>plural</b>, although <b>mass</b>
        agreement is still technically available; amounts greater than this — & all uncounted quantities — are
        grammatically <b>mass</b>. The only exception to this is <b>dual</b> nouns, which are always by definition
        counted & therefore <b>plural</b>.</p>

    {{--    <p>Morphologically, for instance, it's possible to distinguish a <b>feminine plural</b> by the suffix <b>ات--}}
    {{--            (-āt)</b>, but there is only a single plural category in the grammar & this is always gender-neutral.</p>--}}

    <div class="inline-chart">
        <div class="chart-item plr" style="border-radius:1.2rem">
            <div class="chart-title" style="text-transform:uppercase; border-radius:0.8rem; text-align:center">PLR</div>
            <div>SEMANTICALLY</div>
        </div>
        <div class="chart-item" style="border-radius:1.2rem">
            <div class="chart-title" style="text-transform:uppercase; border-radius:0.8rem; text-align:center">COL</div>
            <div>MORPHOLOGICALLY</div>
        </div>
        <div class="chart-item msc" style="border-radius:1.2rem">
            <div class="chart-title" style="text-transform:uppercase; border-radius:0.8rem; text-align:center">MSC</div>
            <div>SYNTACTICALLY</div>
        </div>
    </div>

    <p>3. Third is the behavior of <b>collective</b> nouns (e.g. <b>شجر</b> "trees"), which are not formally
        <b>plural</b>
        but are semantically analogous to <b>plural</b> nouns. Rather than having formally <b>singular</b> &
        <b>plural</b>
        forms, <b>collective</b> nouns have <b>collective</b> & <b>singulative</b> forms that are syntactically
        <b>masculine</b> & <b>feminine</b>, respectively.</p>

    <p>Additionally, nouns are either <b>definite</b> or <b>indefinite</b>.</p>

    <h1 id="demonym">Mass Nouns</h1>
    <p>Some <b>animate</b> nouns that refer to human groups — like <b>ناس (nās "people")</b> — may be treated as masses
        as well; they may be treated either as grammatically plural or feminine (i.e. mass). <b>Demonym Nouns</b> are
        likewise <b>animate</b> <b>mass</b> nouns, so they may be either plural or feminine.</p>

    <h1 id="collective">Collective Nouns</h1>

    <div class="h-badge">
        <div class="badge h2">
            <div class="h">Case</div>
        </div>
    </div>

    <p>As is the case for many other languages, Palestinian Arabic has lost <b>case marking</b> over time. However, it
        maintains some case distinctions in its <b>personal pronouns</b> (see <b>Pronouns</b>).</p>

</div>

<div id="pronoun" class="doc-section">
    <h1>Pronouns</h1>

    <p>Alongside <b>content nouns</b>, the <b>Nouns</b> category includes various types of <b>pronouns</b>. Although <b>pronouns</b>
        generally have a definite antecedent, there is one <b>indefinite pronoun</b> available, in addition to <b>interrogative
            pronouns</b> that by definition have an unknown antecedent.</p>
    <p>Note that <b>demonstrative determiners</b> may be used without a dependent (i.e. as <b>pronouns</b>); however,
        they
        are not repeated here (see <b>Determiners</b>).</p>

    <h2>Personal Pronouns</h2>
    <p><b>Personal Pronouns</b> refer a particular <b>grammatical person</b>. Not only are they the only terms in the
        language inflected for case, but their case greatly affects their form: the <b>nominative</b> set are full
        words, while the <b>genitive & accusative</b> set are clitics. Note that there is one merged <b>genitive-accusative</b>
        case for all grammatical persons, with only the first-person singular retaining a three-way distinction by
        retaining distinct <b>genitive</b> & <b>accusative</b> pronouns.</p>
    <p>Reflexivity in Arabic is usually expressed through its verbal system. However, it may be expressed
        analytically via <b>reflexive pronouns</b> in cases where reflexive verbal forms don't exist; <b>حالـ
            (ħāl-)</b> is properly reflexive, while <b>بعض (baʕḍ)</b> is reciprocal.</p>
    <x-vocabulary title="reflexive">
        <x-term-item :term="$terms->firstWhere('translit', 'nafs-')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħāl-')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'baʕḍ')"/>
    </x-vocabulary>

    <h2>Other Pronouns</h2>
    <x-vocabulary title="interrogative">
        <x-term-item :term="$terms->firstWhere('translit', 'šu')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔēš')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mīn')"/>
    </x-vocabulary>
    <x-vocabulary title="indefinite">
        <x-term-item :term="$terms->firstWhere('translit', 'ħada')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'kamān')"/>
    </x-vocabulary>
    <p>In the case of the <b>relative pronoun</b>, its referent may be indexed by an antecedent or, in its absence,
        by
        the subordinate clause itself. Unlike in Standard Arabic, there is a single <b>relative pronoun</b> that is
        not
        subject to gender & number agreement. However, it does agree with its referent in definiteness; when the
        referent is indefinite, the form of <b>اللي</b> is <b>null</b>.</p>
    <x-vocabulary title="relative">
        <x-term-item :term="$terms->firstWhere('translit', 'l-li')"/>
    </x-vocabulary>
</div>
