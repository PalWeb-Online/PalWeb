<x-page-head title="{{ __('adverbs') }}" blurb="Adverbs are modifiers that do not have a noun as their referent & hence are not subject to
    agreement.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'syntax')">{{ __('syntax') }}</x-link>
    <x-link :href="route('wiki.show', 'adverbs')">{{ __('adverbs') }}</x-link>
</x-page-head>

<p><b>Adverbs</b> are a type of <b>modifier</b> (the other being <b>adjectives</b>) that modify anything that is
    not
    a <b>noun</b>. Like <b>adjectives</b>, by default they come after that which they modify, but word order is
    flexible.</p>

<x-sentence-item eng="he usually goes on Sundays">
    <x-sentence-term arb="بروح" eng="he goes" :term="$terms->firstWhere('translit', 'rāħ')"/>
    <x-sentence-term arb="الأحد" eng="Sunday" :term="$terms->firstWhere('translit', 'l-ʔaħad')"/>
    <x-sentence-term arb="بالعادة" eng="usually" :term="$terms->firstWhere('translit', 'bil-ʕāde')"/>
</x-sentence-item>
<x-sentence-item eng="he usually goes on Sundays">
    <x-sentence-term arb="بالعادة" eng="usually" :term="$terms->firstWhere('translit', 'bil-ʕāde')"/>
    <x-sentence-term arb="بروح" eng="he goes" :term="$terms->firstWhere('translit', 'rāħ')"/>
    <x-sentence-term arb="الأحد" eng="Sunday" :term="$terms->firstWhere('translit', 'l-ʔaħad')"/>
</x-sentence-item>

<p>Morphologically, the productive approach to forming <b>adverbs</b> in <b>MSA</b> is using the <b>ـًا
        ("-an")</b>
    case ending. Often these terms are viable in the dialect as <b>learned borrowings</b>. However, the
    productive
    way of forming <b>adverbs</b> in the dialect is using the <b>بـ ("bi")</b> prefix; many cases of adverb
    doublets
    exist.</p>

<p><b>Prepositions</b> of location that may be used adverbially without a referent are not repeated here.</p>

<x-sentence-item eng="he went inside">
    <x-sentence-term arb="راح" eng="3P.went" :term="$terms->firstWhere('translit', 'rāħ')"/>
    <x-sentence-term arb="جوّا" eng="inside" :term="$terms->firstWhere('translit', 'juwwa')"/>
</x-sentence-item>

<div class="wiki-container">
    <h1>{{ __('inventory') }}</h1>

    <h2>Deictic Adverbs</h2>
    <x-vocabulary title="of location">
        <x-term-item :term="$terms->firstWhere('translit', 'hōn')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'hunāk')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ġād')"/>
    </x-vocabulary>
    <x-vocabulary title="of time">
        <x-term-item :term="$terms->firstWhere('translit', 'hallaʔ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'halʔēt')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔissa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'hassa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħāliyyan')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'kamān šwayy')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'baʕdēn')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'l-yōm')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'l-lēle')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'mbāriħ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bukra')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'la-ʔuddām')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔāxir fatra')"/>
    </x-vocabulary>

    <h2>Modal Adverbs</h2>
    <x-vocabulary title="general">
        <x-term-item :term="$terms->firstWhere('translit', 'ktīr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħabaṭraš')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šwayy')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'kamān')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bass')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'yimkin')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bižūz')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'balki')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'barki')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'b-šikl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'b-surʕa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'b-ṣōt ʕāly')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'biẓ-ẓabṭ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'biṣ-ṣudfe')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bi-balāš')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕal-fāḍy')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕal-ʔāxir')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕal-ʔaġlab')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕa ṭūl')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṭawwāli')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔawām')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'nuṣṣ nuṣṣ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'la-ʔallah')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bil-ktīr')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕal-ʔalīle')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'sawa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'la-ħāl-')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'la-waħd-')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'hēk hēk')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'fiʕliyyan')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ġaṣban ʕann-')"/>
    </x-vocabulary>

    <x-vocabulary title="of frequency">
        <x-term-item :term="$terms->firstWhere('translit', 'dāyman')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bil-ʕāde')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕādatan')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'marrāt')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'nādiran')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ya dōb')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bil-marra')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔabadan')"/>
    </x-vocabulary>

    <x-vocabulary title="of time">
        <x-term-item :term="$terms->firstWhere('translit', 'la-hallaʔ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'lissa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕa bēn')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔabl b-waʔt')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bil-ʔawwal')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔawwalan')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔaxīran')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'fawran')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šwayy šwayy')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'marra waħde')"/>
    </x-vocabulary>

    <h1>Other Adverbs</h1>
    <x-vocabulary title="discursive">
        <p>These <b>Adverbs</b> affect clauses, conveying the speaker's attitude toward the information being conveyed
            (e.g. uncertainty, surprise) & the internal integrity of that information (e.g. causality, contradiction);
            they are used interjectionally & their placement is very flexible.</p>
        <x-term-item :term="$terms->firstWhere('translit', 'barḍo')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'maʕ hēk')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕašān hēk')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'bil-ʕaks')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'lil-ʔasaf')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔaṣlan')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'xuṣūṣan')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'matalan')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'faraḍan')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṭabʕan')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'fiʕlan')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħarfiyyan')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'taʔrīban')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'nawʕan mā')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'b-ṣarāħa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṣarāħatan')"/>
    </x-vocabulary>
    <x-vocabulary title="auxiliary">
        <p>These <b>Adverbs</b> are exclusively used as auxiliaries to modify verbs.</p>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕamm')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕummāl-')"/>
        {{--        i.e. currently --}}
        <x-term-item :term="$terms->firstWhere('translit', 'raħ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ħa-')"/>
    </x-vocabulary>
    <x-vocabulary title="interrogative">
        <x-term-item :term="$terms->firstWhere('translit', 'kīf')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'wēn')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔēmta')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'lēš')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔaddēš')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔakamm')"/>
    </x-vocabulary>
</div>
