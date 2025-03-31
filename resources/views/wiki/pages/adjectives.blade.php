<x-page-head title="{{ __('adjectives') }}" blurb="Adjectives are modifiers that have a noun as their referent & are subject to gender, number &
    definiteness agreement with it.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'syntax')">{{ __('syntax') }}</x-link>
    <x-link :href="route('wiki.show', 'adjectives')">{{ __('adjectives') }}</x-link>
</x-page-head>

<p><b>Adjectives</b> are a type of <b>modifier</b> (the other being <b>adverbs</b>) that modify a noun.
    <b>Adjectives</b> may be split into two subcategories according to their syntactic behavior: <b>positive</b>
    & <b>elative</b>.</p>

{{--    <div class="array">--}}
{{--        <div class="example-sentence">--}}
{{--            <div class="example-eng" style="text-transform: uppercase">--}}
{{--                genderless adjectives--}}
{{--            </div>--}}
{{--            <div class="example-arb"--}}
{{--                 style="flex-flow: row-reverse wrap; justify-content: space-around; font-family: 'Vazirmatn'; font-weight: 700">--}}
{{--                <span style="flex-basis: 40%">تمام</span>--}}
{{--                <span style="flex-basis: 40%">صحّ</span>--}}
{{--                <span style="flex-basis: 40%">غلط</span>--}}
{{--                <span style="flex-basis: 40%">حرام</span>--}}
{{--                <span style="flex-basis: 40%">حلال</span>--}}
{{--                <span style="flex-basis: 40%">سادى</span>--}}
{{--                <span style="flex-basis: 40%">حليوى</span>--}}
{{--                <span style="flex-basis: 40%">ترللّي</span>--}}
{{--                <span style="flex-basis: 40%">طازة</span>--}}
{{--                <span style="flex-basis: 40%">قحّ</span>--}}
{{--                --}}{{--            <span style="flex-basis: 40%">بدريّ</span>--}}
{{--                --}}{{--            <span style="flex-basis: 40%">بكّير</span>--}}
{{--                --}}{{--            this is an adverb --}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="array">--}}
{{--        <div class="example-sentence">--}}
{{--            <div class="example-eng" style="text-transform: uppercase">--}}
{{--                auxiliaries--}}
{{--            </div>--}}
{{--            <div class="example-arb"--}}
{{--                 style="flex-flow: row-reverse wrap; justify-content: space-around; font-family: 'Vazirmatn'; font-weight: 700">--}}
{{--                <span style="flex-basis: 40%">ممكن</span>--}}
{{--                <span style="flex-basis: 40%">لازم</span>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

<p><b>Positive Adjectives</b> — the default form of <b>adjectives</b> — follow the noun they modify & its noun
    modifiers — if any — observing <b>grammatical agreement</b> with the noun they modify (namely, <b>gender</b>,
    <b>number</b> & <b>definiteness</b> agreement).</p>

<x-sentence-item eng="the broken car">
    <x-sentence-term arb="السيّارة" eng="DEF-car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    <x-sentence-term arb="الخربانة" eng="DEF-broken" :term="$terms->firstWhere('translit', 'xarbān')"/>
</x-sentence-item>
<x-sentence-item eng="Sama's new job">
    <x-sentence-term arb="شغل" eng="job" :term="$terms->firstWhere('translit', 'šuġl')"/>
    <x-sentence-term arb="سما" eng="Sama"/>
    <x-sentence-term arb="الجديد" eng="DEF-new" :term="$terms->firstWhere('translit', 'ždīd')"/>
</x-sentence-item>
<x-sentence-item eng="my favorite song">
    <x-sentence-term arb="أغنيتي" eng="my-song" :term="$terms->firstWhere('translit', 'ʔuġniye')"/>
    <x-sentence-term arb="المفضّلة" eng="DEF-favorite" :term="$terms->firstWhere('translit', 'mufaḍḍal')"/>
</x-sentence-item>

<p><b>Elative Adjectives</b> are adjectives that can convey either a comparative or a superlative meaning, depending
    on the syntax. In the syntax of adjectives, they are <b>comparatives</b>. For the use of <b>superlatives</b> —
    which follow a different syntax — see <a href="{{ route('wiki.show', 'numerals') }}">Numerals</a>.
</p>
<p>In Palestinian Arabic, <b>superlatives</b> are never inflected, as they are not subject to grammatical agreement.
    However, although <b>comparatives</b> are technically subject to agreement, they are usually only inflected for
    definiteness; their feminine & plural forms are deprecated (see <b>Morphology</b> below). At the same time,
    attributive <b>comparatives</b> are mainly used for indefinite nouns (2a), as definite <b>comparatives</b>
    (2b) are semantically equivalent to <b>superlatives</b> (3).</p>

{{--    <div class="sentence-mini">--}}
{{--        <div>1</div>--}}
{{--        <div>السيّارة أسرع</div>--}}
{{--    </div>--}}
{{--    <div class="sentence-mini">--}}
{{--        <div>2a</div>--}}
{{--        <div>سيّارة أسرع</div>--}}
{{--    </div>--}}
{{--    <div class="sentence-mini">--}}
{{--        <div>2b</div>--}}
{{--        <div>السيّارة الأسرع</div>--}}
{{--    </div>--}}
{{--    <div class="sentence-mini">--}}
{{--        <div>3</div>--}}
{{--        <div>أسرع سيّارة</div>--}}
{{--    </div>--}}

<x-sentence-item eng="(1) the car is faster">
    <x-sentence-term arb="السيّارة" eng="the-car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    <x-sentence-term arb="أسرع" eng="faster" :term="$terms->firstWhere('translit', 'sarīʕ')"/>
</x-sentence-item>
<x-sentence-item eng="(2) a faster car">
    <x-sentence-term arb="سيّارة" eng="car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    <x-sentence-term arb="أسرع" eng="faster" :term="$terms->firstWhere('translit', 'sarīʕ')"/>
</x-sentence-item>
<div class="array">
    <x-sentence-item eng="(3b) the fastest car">
        <x-sentence-term arb="أسرع" eng="faster" :term="$terms->firstWhere('translit', 'sarīʕ')"/>
        <x-sentence-term arb="سيّارة" eng="car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    </x-sentence-item>
    <x-sentence-item eng="(3a) the fastest car">
        <x-sentence-term arb="السيّارة" eng="car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
        <x-sentence-term arb="الأسرع" eng="faster" :term="$terms->firstWhere('translit', 'sarīʕ')"/>
    </x-sentence-item>
</div>

<div class="wiki-container">
    <h1>Morphology</h1>

    <p><b>Adjectives</b> in Palestinian Arabic have relatively predictable morphological behavior. Most have sound
        plural forms, with only one broken plural pattern associated with a very common adjective pattern. Only two
        truly irregular categories of adjectives exist & they are discussed below.</p>

    <h2>Regular Adjectives</h2>
    <p>By default, <b>Adjectives</b> are marked as feminine by <b>ة (-e)</b> & plural by <b>ين (-īn)</b>; that is, their
        plural forms are sound. Rarely, adjectives may not have any inflected forms, usually due to being in a rare
        pattern (e.g. <b>طازة ṭāza</b>) &/or being derived from a loanword (e.g. <b>تمام tamām</b>).</p>
    <p>Most adjectives fall into a handful of patterns. Patterns with semantic implications are treated separately as
        adjective categories (see <b>Semantics</b> below). Aside from those, there are two neutral adjective patterns:
        <b>CiCiC</b> & <b>CCīC</b> — the latter being notable in that it usually entails broken plural forms.</p>
    <x-vocabulary title="CiCiC">
        <x-inflections
            conjM="زنخ" conjMtr="zinix"
            conjP="زنخين" conjPtr="zinxīn"
        ></x-inflections>
    </x-vocabulary>
    <p><b>CCīC Adjectives</b> usually have the broken plural <b>فعال (CCāC)</b>, but may be sound too; those
        borrowed from Standard Arabic (technically, <b>CaCīC</b>) tend to have sound plurals.</p>
    <x-vocabulary title="CCīC">
        <x-inflections
            conjM="كبير" conjMtr="kbīr"
            conjP="كبار" conjPtr="kbār"
        ></x-inflections>
        <x-inflections
            conjM="سريع" conjMtr="sarīʕ"
            conjP="سريعين" conjPtr="sarīʕīn"
        ></x-inflections>
    </x-vocabulary>
    <p>Sometimes adjectives seem to unexpectedly have broken plural forms, usually due to the term in question having an
        independent use as a noun. In these cases, the broken plural forms apply specifically to the noun senses of the
        term.</p>
    <x-vocabulary>
        <x-term-item :term="$terms->firstWhere('translit', 'mašhūr')"/>
        <x-inflections
            conjM="مشهور" conjMtr="mašhūr"
            conjP="مشهورين" conjPtr="mašhūrīn"
        ></x-inflections>
    </x-vocabulary>
    <x-vocabulary>
        <x-term-item :term="$terms->firstWhere('translit', 'mašhūr')"/>
        <x-inflections
            conjM="مشهور" conjMtr="mašhūr"
            conjP="مشاهير" conjPtr="mašāhīr"
        ></x-inflections>
    </x-vocabulary>

    <h2>Demonym Adjectives</h2>
    <p><b>Demonym Adjectives</b> are <b>relative adjectives</b> derived from <a
            href="{{ route('wiki.show', 'nouns') }}">Demonym
            Nouns</a> or the names of countries, cities, etc. In theory, <b>relative adjectives</b> have sound plural
        forms, but <b>Demonym Adjectives</b> that are derived from a <b>Demonym Nouns</b> have their plural forms
        supplied by the noun itself. Hence, some of these terms have plural forms that are neither sound nor broken, but
        rather are back-formed by removing the <b>يّ (-yy)</b> suffix & sometimes applying some other transformation.
    </p>
    <x-vocabulary>
        <x-term-item :term="$terms->firstWhere('translit', 'fransa')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'fransāwi')"/>
        <x-inflections
            conjM="فرنساويّ" conjMtr="fransāwi"
            conjF="فرنساويّة" conjFtr="fransāwiyye"
            conjP="فرنساويّين" conjPtr="fransāwiyyīn"
        ></x-inflections>
    </x-vocabulary>
    <x-vocabulary>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕarab')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʕarabi')"/>
        <x-inflections
            conjM="عربيّ" conjMtr="ʕarabi"
            conjF="عربيّة" conjFtr="ʕarabiyye"
            conjP="عرب" conjPtr="ʕarab"
        ></x-inflections>
    </x-vocabulary>

    <x-sentence-item eng="French guys">
        <x-sentence-term arb="الشباب" eng="DEF-guys" :term="$terms->firstWhere('translit', 'šabb')"/>
        <x-sentence-term arb="الفرنساويّين" eng="DEF-French" :term="$terms->firstWhere('translit', 'fransāwi')"/>
    </x-sentence-item>
    <x-sentence-item eng="Arab guys">
        <x-sentence-term arb="الشباب" eng="DEF-guys" :term="$terms->firstWhere('translit', 'šabb')"/>
        <x-sentence-term arb="العرب" eng="DEF-Arab" :term="$terms->firstWhere('translit', 'ʕarabi')"/>
    </x-sentence-item>

    <p>Regardless of the form of the plural itself, all <b>Demonym Adjectives</b> are characterized by the fact that
        their plural forms may only be used for <b>animate</b> nouns; it's always ungrammatical to use the plural form
        of a <b>Demonym Adjective</b> for a non-<b>animate</b> noun. Although non-<b>animate</b> plural nouns are
        usually grammatically feminine & only optionally plural if counted, dual nouns are always forcibly grammatically
        plural in Palestinian Arabic. Hence, the diseappearance of dual inflections for adjectives in Palestinian Arabic
        has left a lexical gap wherein <b>Demonym Adjectives</b> cannot be used with non-<b>animate</b> dual nouns
        without applying dual inflection to adjectives, something that otherwise only occurs in Standard Arabic.</p>

    {{--    <div class="sentence-mini">--}}
    {{--        <div>1</div>--}}
    {{--        <div>الشباب العرب بأميركا عمّ بطلعو كثير عالمظاهرات</div>--}}
    {{--    </div>--}}

    <p>In a minority of cases, the plural form is formed by placing the term's root in any of a variety of broken
        plural patterns usually reserved for nouns.</p>

    <h2>Defect Adjectives</h2>
    <p><b>Defect Adjectives</b> — which are in the <b>أفعل (ʔaCCaC)</b> pattern — always have <b>فعلا (CaCCa)</b>
        feminine forms & <b>فعل (CuCC)</b> plural forms. Note that these
        inflectional patterns don't apply to other terms in this pattern that are not adjectives (e.g. <b>أزعر
            ʔazʕar</b>).</p>
    <x-vocabulary title="DEFECT ADJECTIVES">
        <x-inflections
            conjM="أحمر" conjMtr="ʔaħmar"
            conjF="حمرا" conjFtr="ħamra"
            conjP="حمر" conjPtr="ħumr"
        ></x-inflections>
    </x-vocabulary>

    <p><b>Elative Adjectives</b> are, by definition, in the <b>أفعل (ʔaCCaC)</b> pattern; they are <b>Defect
            Adjectives</b> morphologically. Although this means they have the aforementioned inflected forms
        eymologically, they are not inflected in practice. Only <b>Defect Adjectives</b> that are <b>positive
            adjectives</b> are used in their distinct feminine & plural forms.</p>

    <p>As for the formation of <b>Elative Adjectives</b>, they are formed productively by placing the root of any
        word in this pattern. Alternatively — like in English — <b>أكثر (ʔaktar "more")</b> may be used, most commonly
        if the <b>positive form</b> has more than three consonants or otherwise doesn't fit into the pattern.</p>
    <div class="array">
        <x-sentence-item eng="smarter">
            <x-sentence-term arb="أشطر" eng="smarter" :term="$terms->firstWhere('translit', 'šāṭir')"/>
        </x-sentence-item>
        <x-sentence-item eng="smart">
            <x-sentence-term arb="شاطر" eng="smart" :term="$terms->firstWhere('translit', 'šāṭir')"/>
        </x-sentence-item>
    </div>
    <div class="array">
        <x-sentence-item eng="more of an idiot">
            <x-sentence-term arb="أحمر" eng="donkey-er" :term="$terms->firstWhere('translit', 'ħmār')"/>
        </x-sentence-item>
        <x-sentence-item eng="an idiot">
            <x-sentence-term arb="حمار" eng="donkey" :term="$terms->firstWhere('translit', 'ħmār')"/>
        </x-sentence-item>
    </div>
    <div class="array">
        <x-sentence-item eng="busier">
            <x-sentence-term arb="مشغول" eng="busy" :term="$terms->firstWhere('translit', 'mašġūl')"/>
            <x-sentence-term arb="أكثر" eng="more" :term="$terms->firstWhere('translit', 'ʔaktar')"/>
        </x-sentence-item>
        <x-sentence-item eng="busy">
            <x-sentence-term arb="مشغول" eng="busy" :term="$terms->firstWhere('translit', 'mašġūl')"/>
        </x-sentence-item>
    </div>
</div>

<div class="wiki-container">
    <h1>Semantics</h1>

    <p>Some of the aforementioned adjective patterns have semantic associations.</p>

    <h2>Relative Adjectives</h2>
    <p><b>Relative Adjectives</b> are formed by attaching the suffix <b>يّ (-yy)</b> to a noun, indicating something
        that
        is of or related to that noun.</p>

    <x-vocabulary>
        <x-term-item :term="$terms->firstWhere('translit', 'falasṭīn')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'falasṭīni')"/>
        <x-inflections
            conjM="فلسطينيّ" conjMtr="falasṭīni"
            conjF="فلسطينيّة" conjFtr="falasṭīniyye"
            conjP="فلسطينيّين" conjPtr="falasṭīniyyīn"
        ></x-inflections>
    </x-vocabulary>

    <p>Additionally, nouns may be formed from <b>Relative Adjectives</b> in turn by attaching the suffix <b>ة (-e)</b>.
        Known as <b>Nominalized Adjectives</b>, these nouns usually refer to an abstraction of the <b>Relative
            Adjective</b>
        or — if the latter refers to a person — to a human collective.</p>

    <x-vocabulary>
        <x-term-item :term="$terms->firstWhere('translit', 'masīħi')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'masīħiyye')"/>
        <x-sentence-item eng="alcohol isn't sinful for Christians">
            <x-sentence-term arb="الكحول" eng="the-alcohol" :term="$terms->firstWhere('translit', 'kuħūl')"/>
            <x-sentence-term arb="مش" eng="not" :term="$terms->firstWhere('translit', 'miš')"/>
            <x-sentence-term arb="حرام" eng="immoral" :term="$terms->firstWhere('translit', 'ħarām')"/>
            <x-sentence-term arb="عند" eng="at" :term="$terms->firstWhere('translit', 'ʕind')"/>
            <x-sentence-term arb="المسيحيّة" eng="Christians" :term="$terms->firstWhere('translit', 'masīħiyye')"/>
        </x-sentence-item>
    </x-vocabulary>

    <h2>Intensive Adjectives</h2>
    <p><b>Intensive Adjectives</b> are in the <b>فعلان (CaCCān)</b> pattern. Usually associated with emotions, this
        adjective category indicates a current state (i.e. how something is currently or lately) — usually of a person —
        constrasting with adjectives that refer to intrinsic qualities &/or are used for inanimate nouns.</p>

    <div class="array">
        <x-sentence-item eng="he's good-looking">
            <x-sentence-term arb="هو" eng="he" :term="$terms->firstWhere('translit', 'huwwe')"/>
            <x-sentence-term arb="حلو" eng="good-looking" :term="$terms->firstWhere('translit', 'ħilu')"/>
        </x-sentence-item>
        <x-sentence-item eng="he's a cold person">
            <x-sentence-term arb="هو" eng="he" :term="$terms->firstWhere('translit', 'huwwe')"/>
            <x-sentence-term arb="بارد" eng="cold" :term="$terms->firstWhere('translit', 'bārid')"/>
        </x-sentence-item>
    </div>
    <div class="array">
        <x-sentence-item eng="he's looking good">
            <x-sentence-term arb="هو" eng="he" :term="$terms->firstWhere('translit', 'huwwe')"/>
            <x-sentence-term arb="حليان" eng="looking good" :term="$terms->firstWhere('translit', 'ħalyān')"/>
        </x-sentence-item>
        <x-sentence-item eng="he's feeling cold">
            <x-sentence-term arb="هو" eng="he" :term="$terms->firstWhere('translit', 'huwwe')"/>
            <x-sentence-term arb="بردان" eng="cold" :term="$terms->firstWhere('translit', 'bardān')"/>
        </x-sentence-item>
    </div>

    <p><b>Intensive Adjectives</b> are often associated with <b>A2 Verbs</b>, which tend to indicate something entering
        into a state (i.e. becoming a certain way).</p>

    <x-vocabulary title="INTENSIVE ADJECTIVES">
        <x-term-item :term="$terms->firstWhere('translit', 'ziʕil')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'zaʕlān')"/>
        <x-inflections
            conjM="زعلان" conjMtr="zaʕlān"
            conjF="زعلانة" conjFtr="zaʕlāne"
            conjP="زعلانين" conjPtr="zaʕlānīn"
        ></x-inflections>
    </x-vocabulary>

    <h2>Active Participles</h2>
    <p><b>Active Participles</b> are morphologically like other <b>adjectives</b>. Semantically, they stand out in that
        they convey a verbal meaning, whether a <b>present continuous</b> or <b>past perfect</b> meaning.</p>

    <h2>Passive Participles</h2>

    <h2>Defect Adjectives</h2>
    <p><b>Defect Adjectives</b> that are strictly <b>positive</b> (i.e. not <b>Elative Adjectives</b>) tend to refer to
        certain bodily characteristics & impairments; the six primary color terms are in this pattern as well. Still,
        these are relatively infrequent & the pattern is only productive for the formation of <b>Elative Adjectives</b>.
    </p>
    <x-vocabulary title="DEFECT ADJECTIVES">
        <x-term-item :term="$terms->firstWhere('translit', 'ʔaʕma')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔaṣlaʕ')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔaħmar')"/>
    </x-vocabulary>
</div>

{{--<p><b>Elative Adjectives</b>--}}
{{--    <span class="chart-item elt" style="border-radius:1.2rem">--}}
{{--        <span class="chart-title"--}}
{{--              style="text-transform:uppercase; border-radius:0.8rem; text-align:center">--}}
{{--            elative--}}
{{--        </span>--}}
{{--    </span>--}}
{{--    are syntactically very distinct from <b>Positive Adjectives</b>: they head an--}}
{{--    inherently definite noun phrase. However, they may also be used syntactically as <b>Positive Adjectives</b>. In--}}
{{--    the case of "true" elatives — as opposed to <b>ordinal numbers</b> — using the adjective in the elative--}}
{{--    structure yields a superlative meaning (e.g. <b>أكبر بيت "the biggest house"</b>), whereas using it in the--}}
{{--    default structure yields a comparative meaning (e.g. <b>بيت أكبر "a bigger house"</b>).</p>--}}
{{--<p>As suggested earlier,--}}
{{--    <b>ordinal numbers</b> are syntactically <b>Elative Adjectives</b> insofar as they may use the elative--}}
{{--    structure, although they are not semantically comparative or superlative.</p>--}}
{{--<p>In the <a href="/dictionary">Dictionary</a>, <b>Defect Adjectives</b>, <b>Active Participles</b> & <b>Elative--}}
{{--        Adjectives</b> (inc. <b>Ordinal Numbers</b>) are all categorized as <b>Adjectives</b>, with the subtype--}}
{{--    indicated in the headword.</p>--}}
