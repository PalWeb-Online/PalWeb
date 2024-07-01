<x-page-head title="{{ __('verbs') }}" blurb="Verbs are subject to person agreement with the sentence subject. They have tense & mood. Every
    sentence has one.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'syntax')">{{ __('syntax') }}</x-link>
    <x-link :href="route('wiki.show', 'verbs')">{{ __('verbs') }}</x-link>
</x-page-head>

<div id="copula" class="doc-section">
    <h1>Copulae</h1>

    <h1>THE STATIVE COPULA</h1>

    <p>In Arabic, a <b>null copula</b> is used to define the state of a subject; this is the <b>stative
            copula</b>. By definition, the subject of a <b>stative copula</b> must be definite.</p>

    <x-sentence eng="the children are happy">
        <x-sentence-term arb="الولاد" eng="the-children" :term="$terms->firstWhere('translit', 'wlād')"/>
        <x-sentence-term arb="" eng="="/>
        <x-sentence-term arb="مبسوطين" eng="P.happy" :term="$terms->firstWhere('translit', 'mabsūṭ')"/>
    </x-sentence>

    <p>Since the <b>stative copula</b> is not an overt verb, these sentences are usually referred to as <b>nominal
            sentences</b>; these are distinct only in that they demand an overt subject. Having said that, the <b>stative
            copula</b> does occupy the head of a verbal phrase that may be modified by auxiliaries; hence, it may be
        present even in sentences with an overt verb (i.e. <b>verbal sentences</b>). In view of this, all sentences with
        <b>copulae</b> are specifically referred to as <b>copular sentences</b>.</p>

    <x-sentence eng="the children were happy">
        <x-sentence-term arb="الولاد" eng="the-children" :term="$terms->firstWhere('translit', 'wlād')"/>
        <x-sentence-term arb="كانو" eng="3P.were" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="" eng="="/>
        <x-sentence-term arb="مبسوطين" eng="P.happy" :term="$terms->firstWhere('translit', 'mabsūṭ')"/>
    </x-sentence>

    <p>As in the foregoing sentence, the <b>stative copula</b> may be modified by an <b>aspectual verb</b> to indicate
        the stability of a state over time. While the unmarked <b>كان (kān)</b> indicates a static state, terms like <b>صار
            (ṣār)</b> & <b>بطّل (baṭṭal)</b> indicate the beginning & end of a state, respectively; other <b>aspectual
            verbs</b> exist as well. As usual, verbal auxiliaries agree with the subject.</p>

    <x-sentence eng="the children became happy">
        <x-sentence-term arb="الولاد" eng="the-children" :term="$terms->firstWhere('translit', 'wlād')"/>
        <x-sentence-term arb="صارو" eng="3P.became" :term="$terms->firstWhere('translit', 'ṣār')"/>
        <x-sentence-term arb="" eng="="/>
        <x-sentence-term arb="مبسوطين" eng="P.happy" :term="$terms->firstWhere('translit', 'mabsūṭ')"/>
    </x-sentence>

    <x-sentence eng="the children ceased being happy">
        <x-sentence-term arb="الولاد" eng="the-children" :term="$terms->firstWhere('translit', 'wlād')"/>
        <x-sentence-term arb="بطّلو" eng="3P.ceased" :term="$terms->firstWhere('translit', 'baṭṭal')"/>
        <x-sentence-term arb="" eng="="/>
        <x-sentence-term arb="مبسوطين" eng="P.happy" :term="$terms->firstWhere('translit', 'mabsūṭ')"/>
    </x-sentence>

    <p>As for negation, it applies to the head of the highest verbal phrase in the structure. When the <b>null
            copula</b> itself is negated thus, the negative particle <b>مش (miš)</b> is produced.</p>

    <x-sentence eng="they weren't happy">
        <x-sentence-term arb="مـ" eng="not" :term="$terms->firstWhere('translit', 'mā')"/>
        <x-sentence-term arb="كانو" eng="3P.were" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="ـش" eng="not" :term="$terms->firstWhere('translit', '-š')"/>
        <x-sentence-term arb="" eng="="/>
        <x-sentence-term arb="مبسوطين" eng="P.happy" :term="$terms->firstWhere('translit', 'mabsūṭ')"/>
    </x-sentence>

    <x-sentence eng="(they) aren't happy">
        <x-sentence-term arb="مـ" eng="not" :term="$terms->firstWhere('translit', 'mā')"/>
        <x-sentence-term arb="" eng="="/>
        <x-sentence-term arb="ـش" eng="not" :term="$terms->firstWhere('translit', '-š')"/>
        <x-sentence-term arb="مبسوطين" eng="P.happy" :term="$terms->firstWhere('translit', 'mabsūṭ')"/>
    </x-sentence>

    <x-vocabulary title="particles">
        <x-term :term="$terms->firstWhere('translit', 'miš')"/>
    </x-vocabulary>

    <div class="line" style="height: 0.2rem; margin-top: 2.4rem"></div>
    <h1>THE EXISTENTIAL COPULA</h1>

    <p>In Palestinian Arabic, the expletive <b>فيه (fīh)</b> is inserted after the <b>null copula</b>
        to create an <b>existential copula</b> (i.e. <b>"there be"</b>). One should not be led to misidentify the
        function of <b>فيه (fīh)</b> by the fact that the <b>null copula</b> is not pronounced in the <b>Present
            Tense</b>.
    </p>

    <x-sentence eng="there are problems">
        <x-sentence-term arb="" eng="="/>
        <x-sentence-term arb="فيه" eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
        <x-sentence-term arb="ولاد" eng="children" :term="$terms->firstWhere('translit', 'wlād')"/>
    </x-sentence>

    <p>Since an <b>existential copula</b> is fundamentally an impersonal verb, it has no subject but rather a referent
        that is necessarily indefinite. Hence, verbal auxiliaries are never inflected.</p>

    <x-sentence eng="there were children">
        <x-sentence-term arb="كان" eng="was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="" eng="="/>
        <x-sentence-term arb="فيه" eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
        <x-sentence-term arb="ولاد" eng="children" :term="$terms->firstWhere('translit', 'wlād')"/>
    </x-sentence>

    <p>Consequently, there is no ambiguity between a sentence with an <b>existential copula</b> & a sentence
        with a <b>stative copula</b> that incidentally features <b>فيه (fīh)</b> as its complement.</p>

    <x-sentence eng="the children were in it">
        <x-sentence-term arb="الولاد" eng="the-children" :term="$terms->firstWhere('translit', 'wlād')"/>
        <x-sentence-term arb="كانو" eng="3P.were" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="" eng="="/>
        <x-sentence-term arb="فيه" eng="in-3M" :term="$terms->firstWhere('translit', 'fīh')"/>
    </x-sentence>

    <p>Although negation is resolved as expected, the expletive is included in the negative phrase & undergoes a sound
        change that shortens its phonemically long final vowel.</p>

    <x-sentence eng="there weren't any children">
        <x-sentence-term arb="مـ" eng="not" :term="$terms->firstWhere('translit', 'mā')"/>
        <x-sentence-term arb="كان" eng="was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="ـش" eng="not" :term="$terms->firstWhere('translit', '-š')"/>
        <x-sentence-term arb="" eng="="/>
        <x-sentence-term arb="فيه" eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
        <x-sentence-term arb="ولاد" eng="children" :term="$terms->firstWhere('translit', 'wlād')"/>
    </x-sentence>

    <x-sentence eng="there aren't any children">
        <x-sentence-term arb="مـ" eng="not" :term="$terms->firstWhere('translit', 'mā')"/>
        <x-sentence-term arb="" eng="="/>
        <x-sentence-term arb="فيه" eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
        <x-sentence-term arb="ـش" eng="not" :term="$terms->firstWhere('translit', '-š')"/>
        <x-sentence-term arb="ولاد" eng="children" :term="$terms->firstWhere('translit', 'wlād')"/>
    </x-sentence>

    <p>Notice that these negation patterns can only be accounted for if the <b>null copula</b> is analyzed as
        syntactically distinct from <b>كان (kān)</b>. Otherwise, one should find <b>مكانو فش</b> or <b>مش فيه</b> —
        neither of which are grammatical forms of verbal negation for the <b>existential copula</b>.</p>

    <x-vocabulary title="particles">
        <x-term :term="$terms->firstWhere('translit', 'fīh')"/>
    </x-vocabulary>

    <div class="line" style="height: 0.2rem; margin-top: 2.4rem"></div>
    <h1>THE POSSESSIVE COPULA</h1>

    <p>In addition to the expletive <b>فيه (fīh)</b>, there are a handful of prepositions that may complement an <b>existential
            copula</b>; these <b>verbal prepositions</b> are used to build a <b>possessive copula</b> that conveys its
        meaning by situating the
        existence of something at the possessor's location.</p>

    <x-sentence eng="she has problems">
        <x-sentence-term arb="" eng="=" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="فيه" eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
        <x-sentence-term arb="عندها" eng="at-her" :term="$terms->firstWhere('translit', 'ʔind')"/>
        <x-sentence-term arb="مشاكل" eng="problems" :term="$terms->firstWhere('translit', 'muškile')"/>
    </x-sentence>

    <p>Although semantically this might seem like a transitive verb, in reality it is an <b>existential copula</b> that
        is pinned to the semantic agent by the preposition. Since there is no subject syntactically, verbal auxiliaries
        are not inflected here either:</p>

    <x-sentence eng="she had problems">
        <x-sentence-term arb="كان" eng="was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="" eng="="/>
        <x-sentence-term arb="فيه" eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
        <x-sentence-term arb="عندها" eng="at-her" :term="$terms->firstWhere('translit', 'ʔind')"/>
        <x-sentence-term arb="مشاكل" eng="problems" :term="$terms->firstWhere('translit', 'muškile')"/>
    </x-sentence>

    <p>Negation proceeds as expected:</p>

    <x-sentence eng="she doesn't have problems">
        <x-sentence-term arb="مـ" eng="not" :term="$terms->firstWhere('translit', 'mā')"/>
        <x-sentence-term arb="" eng="="/>
        <x-sentence-term arb="فيه" eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
        <x-sentence-term arb="ـش" eng="not" :term="$terms->firstWhere('translit', '-š')"/>
        <x-sentence-term arb="عندها" eng="at-her" :term="$terms->firstWhere('translit', 'ʔind')"/>
        <x-sentence-term arb="مشاكل" eng="problems" :term="$terms->firstWhere('translit', 'muškile')"/>
    </x-sentence>

    <x-sentence eng="she didn't have problems">
        <x-sentence-term arb="مـ" eng="not" :term="$terms->firstWhere('translit', 'mā')"/>
        <x-sentence-term arb="كان" eng="was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="ـش" eng="not" :term="$terms->firstWhere('translit', '-š')"/>
        <x-sentence-term arb="" eng="="/>
        <x-sentence-term arb="فيه" eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
        <x-sentence-term arb="عندها" eng="at-her" :term="$terms->firstWhere('translit', 'ʔind')"/>
        <x-sentence-term arb="مشاكل" eng="problems" :term="$terms->firstWhere('translit', 'muškile')"/>
    </x-sentence>

    <p>Given that the complement of the <b>existential copula</b> is necessarily indefinite, the presence of <b>فيه
            (fīh)</b> in the <b>possessive copula</b> forces it to require an indefinite complement. However, the <b>verbal
            preposition</b> can supply the existential meaning on its own, so the expletive may be removed from the
        construction & this requirement neutralized.</p>

    <x-sentence eng="she had the car">
        <x-sentence-term arb="كان" eng="was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="" eng="="/>
        <x-sentence-term arb="" eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
        <x-sentence-term arb="عندها" eng="at-her" :term="$terms->firstWhere('translit', 'ʔind')"/>
        <x-sentence-term arb="السيّارة" eng="the-car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    </x-sentence>

    <x-sentence eng="she has the car">
        <x-sentence-term arb="" eng="=" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="" eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
        <x-sentence-term arb="عندها" eng="at-her" :term="$terms->firstWhere('translit', 'ʔind')"/>
        <x-sentence-term arb="السيّارة" eng="the-car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    </x-sentence>

    <p>In some cases, <b>verbal prepositions</b> are indistinguishable from <b>pseudo-verbs</b>. When all other possible
        elements of the <b>possessive copula</b> are null, the preposition — syntactically a verbal complement – moves
        to fill the position of the verb itself, where it may be negated:</p>

    <x-sentence eng="she has the car">
        <x-sentence-term arb="عندها" eng="3F.has" :term="$terms->firstWhere('translit', 'ʔind')"/>
        <x-sentence-term arb="السيّارة" eng="the-car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    </x-sentence>

    <x-sentence eng="she doesn't have the car">
        <x-sentence-term arb="مـ" eng="not" :term="$terms->firstWhere('translit', 'mā')"/>
        <x-sentence-term arb="عندها" eng="3F.has" :term="$terms->firstWhere('translit', 'ʔind')"/>
        <x-sentence-term arb="ـش" eng="not" :term="$terms->firstWhere('translit', '-š')"/>
        <x-sentence-term arb="السيّارة" eng="the-car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    </x-sentence>

    <p>Although <b>verbal prepositions</b> are categorized in the <b>Dictionary</b> alongside
        <b>pseudo-verbs</b> for the sake of practicality, only by distinguishing between the two can we account for the
        differences in their
        syntactic behavior.</p>

    <x-vocabulary title="verbal prepositions">
        <x-term :term="$terms->firstWhere('translit', 'ʕindo')"/>
        <x-term :term="$terms->firstWhere('translit', 'maʕo')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔilo')"/>
        <x-term :term="$terms->firstWhere('translit', 'fiyyo')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕalēh')"/>
    </x-vocabulary>

    {{--    <p>Additional idiomatic verbs may be built from these idioms, as in <b>إله عليه</b> ("he owes him").</p>--}}

</div>

<div id="pseudo" class="doc-section">
    <h1>pseudo-verbs</h1>
    <p>Alongside etymological verbs, a few other terms in Palestinian Arabic have been grammaticalized as verbs. What
        these <b>pseudo-verbs</b> have in common
        is that — unlike etymological verbs — they are conjugated using <b>clitic pronouns</b> rather
        than by way of inflectional morphology.</p>

    <div class="array">
        <div class="example-sentence">
            <div class="example-eng" style="text-transform: uppercase">
                PSEUDO-VERBS
            </div>
            <div class="example-arb"
                 style="flex-flow: row-reverse wrap; justify-content: space-around; font-family: 'Vazirmatn'; font-weight: 700">
                <span style="flex-basis: 80%">بدّه</span>
                <span style="flex-basis: 80%">شكله</span>
                <span style="flex-basis: 40%">عنده</span>
                <span style="flex-basis: 40%">معه</span>
                <span style="flex-basis: 40%">إله</span>
                <span style="flex-basis: 40%">فيّه</span>
                <span style="flex-basis: 80%">عليه</span>
            </div>
        </div>
    </div>

    <p>It should be noted that <b>verbal prepositions</b> are categorized here as <b>pseudo-verbs</b>. However, at heart
        they are prepositions used to form <b>possessive
            copulae</b>, which are only reanalyzed as <b>pseudo-verbs</b> when all other elements of the underlying
        structure are null; they are therefore subject to major restrictions as <b>pseudo-verbs</b>, including the fact
        that they cannot be directly modified by auxiliaries.</p>

    <div class="array">
        <div class="example-sentence">
            <div class="example-eng" style="text-transform: uppercase">
                VERBAL PREPOSITIONS
            </div>
            <div class="example-arb"
                 style="flex-flow: row-reverse wrap; justify-content: space-around; font-family: 'Vazirmatn'; font-weight: 700">
                <span style="flex-basis: 40%">عنده</span>
                <span style="flex-basis: 40%">معه</span>
                <span style="flex-basis: 40%">إله</span>
                <span style="flex-basis: 40%">فيّه</span>
                <span style="flex-basis: 80%">عليه</span>
            </div>
        </div>
    </div>

    <p>In general, the grammaticalization of <b>pseudo-verbs</b> is visible in that they may be modified by auxiliaries
        that agree with the semantic agent rather than with the <b>pseudo-verb</b> itself.</p>

    <x-sentence eng="Maryam was speaking">
        <x-sentence-term arb="مريم" eng="Maryam"/>
        <x-sentence-term arb="كانت" eng="3F.was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="تحكي" eng="3F.speaks" :term="$terms->firstWhere('translit', 'ħaka')"/>
    </x-sentence>

    <x-sentence eng="Maryam wanted water">
        <x-sentence-term arb="مريم" eng="Maryam"/>
        <x-sentence-term arb="كانت" eng="3F.was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="بدّها" eng="3F.want" :term="$terms->firstWhere('translit', 'bidd-')"/>
        <x-sentence-term arb="ميّ" eng="water" :term="$terms->firstWhere('translit', 'mayy')"/>
    </x-sentence>

    <x-sentence eng="Maryam seemed upset">
        <x-sentence-term arb="مريم" eng="Maryam"/>
        <x-sentence-term arb="كانت" eng="3F.was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="شكلها" eng="3F.seems" :term="$terms->firstWhere('translit', 'šikl-')"/>
        <x-sentence-term arb="زعلانة" eng="F.upset" :term="$terms->firstWhere('translit', 'zaʕlān')"/>
    </x-sentence>

    <p>Having said that, the fact that auxiliaries may be used uninflected is a testament to the origin of <b>pseudo-verbs</b>
        & evidence of their incomplete grammaticalization.</p>

    <x-sentence eng="Maryam wanted water">
        <x-sentence-term arb="مريم" eng="Maryam"/>
        <x-sentence-term arb="كان" eng="was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="بدّها" eng="3F.want" :term="$terms->firstWhere('translit', 'bidd-')"/>
        <x-sentence-term arb="ميّ" eng="water" :term="$terms->firstWhere('translit', 'mayy')"/>
    </x-sentence>

    <x-sentence eng="Maryam seemed upset">
        <x-sentence-term arb="مريم" eng="Maryam"/>
        <x-sentence-term arb="كان" eng="was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="شكلها" eng="3F.seems" :term="$terms->firstWhere('translit', 'šikl-')"/>
        <x-sentence-term arb="زعلانة" eng="F.upset" :term="$terms->firstWhere('translit', 'zaʕlān')"/>
    </x-sentence>

    <p>Similarly, <b>verbal prepositions</b> must be modified by an uninflected auxiliary due to the underlying syntax
        of the <b>possessive copula</b>; here, they are not <b>pseudo-verbs</b>.</p>

    <x-sentence eng="Maryam had a car">
        <x-sentence-term arb="مريم" eng="Maryam"/>
        <x-sentence-term arb="كان" eng="was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="عندها" eng="3F.has" :term="$terms->firstWhere('translit', 'ʕind-')"/>
        <x-sentence-term arb="سيّارة" eng="a car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    </x-sentence>

    <p>With the single exception of <b>شكله (šiklo)</b>, all <b>pseudo-verbs</b> may be directly negated. However, <b>verbal
            prepositions</b> in the <b>possessive copula</b> are not; instead, negation attaches to the head of the
        highest verbal phrase.</p>

    <x-sentence eng="I don't want it">
        <x-sentence-term arb="مـ" eng="not" :term="$terms->firstWhere('translit', 'mā')"/>
        <x-sentence-term arb="بدّي" eng="1S.want" :term="$terms->firstWhere('translit', 'bidd-')"/>
        <x-sentence-term arb="ـش" eng="not" :term="$terms->firstWhere('translit', 'š')"/>
        <x-sentence-term arb="ايّاه" eng="M.it" :term="$terms->firstWhere('translit', 'yyā-')"/>
    </x-sentence>

    <x-sentence eng="I don't have a car">
        <x-sentence-term arb="مـ" eng="not" :term="$terms->firstWhere('translit', 'mā')"/>
        <x-sentence-term arb="عندي" eng="1S.have" :term="$terms->firstWhere('translit', 'ʕind-')"/>
        <x-sentence-term arb="ـش" eng="not" :term="$terms->firstWhere('translit', 'š')"/>
        <x-sentence-term arb="سيّارة" eng="a car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    </x-sentence>

    <x-sentence eng="I don't have a car">
        <x-sentence-term arb="مـ" eng="not" :term="$terms->firstWhere('translit', 'mā')"/>
        <x-sentence-term arb="" eng="="/>
        <x-sentence-term arb="فيه" eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
        <x-sentence-term arb="ـش" eng="not" :term="$terms->firstWhere('translit', '-š')"/>
        <x-sentence-term arb="عندي" eng="1S.have" :term="$terms->firstWhere('translit', 'ʕind-')"/>
        <x-sentence-term arb="سيّارة" eng="a car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    </x-sentence>

    <h2>TO WANT</h2>
    <p>With regard to <b>بدّه (biddo)</b>, it is a transitive verb that requires an object. Note that the interference
        of the
        <b>clitic pronoun</b> always forces the direct object onto the <b>ايّا (yyā-)</b> affix.</p>

    <x-sentence eng="I want it">
        <x-sentence-term arb="بدّي" eng="1S.want" :term="$terms->firstWhere('translit', 'bidd-')"/>
        <x-sentence-term arb="ايّاه" eng="M.it" :term="$terms->firstWhere('translit', 'yyā-')"/>
    </x-sentence>

    <x-vocabulary title="to want">
        <x-term :term="$terms->firstWhere('translit', 'biddo')"/>
    </x-vocabulary>

    <h2>TO SEEM</h2>
    <p>With regard to <b>شكله (šiklo "to seem")</b>, it is a raising verb that raises a constituent to the subject
        position; if clause-initial, it may refer to a null
        subject as well.</p>
    <x-sentence eng="the car seems new">
        <x-sentence-term arb="السيّارة" eng="the-car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
        <x-sentence-term arb="شكلها" eng="3F.seems" :term="$terms->firstWhere('translit', 'šikl-')"/>
        <x-sentence-term arb="جديدة" eng="F.new" :term="$terms->firstWhere('translit', 'jdīd')"/>
    </x-sentence>
    <x-sentence eng="it seems the car is new">
        <x-sentence-term arb="شكله" eng="3M.seems" :term="$terms->firstWhere('translit', 'šikl-')"/>
        <x-sentence-term arb="السيّارة" eng="the-car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
        <x-sentence-term arb="جديدة" eng="F.new" :term="$terms->firstWhere('translit', 'jdīd')"/>
    </x-sentence>
    <p>In the case of the null-subject construction, the tense of the predicate is flexible. However, the tense
        of <b>شكله (šiklo)</b> itself applies to the entire subordinate clause, so the double-marking of tense would be
        perceived as either redundant or semantically confusing.</p>
    <x-sentence eng="it seems the car was new">
        <x-sentence-term arb="شكله" eng="3M.seems" :term="$terms->firstWhere('translit', 'šikl-')"/>
        <x-sentence-term arb="السيّارة" eng="the-car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
        <x-sentence-term arb="كانت" eng="3F.was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="جديدة" eng="F.new" :term="$terms->firstWhere('translit', 'jdīd')"/>
    </x-sentence>
    <x-sentence eng="it seemed the car was new">
        <x-sentence-term arb="كان" eng="was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="شكله" eng="3M.seems" :term="$terms->firstWhere('translit', 'šikl-')"/>
        <x-sentence-term arb="السيّارة" eng="the-car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
        <x-sentence-term arb="جديدة" eng="F.new" :term="$terms->firstWhere('translit', 'jdīd')"/>
    </x-sentence>
    <x-vocabulary title="to seem">
        <x-term :term="$terms->firstWhere('translit', 'šiklo')"/>
    </x-vocabulary>
</div>

<div class="doc-section">
    <h1>auxiliaries</h1>
    <x-vocabulary title="aspectual">
        <x-term :term="$terms->firstWhere('translit', 'kān')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṣār')"/>
        <x-term :term="$terms->firstWhere('translit', 'baṭṭal')"/>
        <x-term :term="$terms->firstWhere('translit', 'ḍall')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔaʕad')"/>
    </x-vocabulary>
    <p>In Arabic, many intransitive verbs have <b>causative</b> counterparts. However, the <b>causative
            auxiliary</b> <b>خلّى (xalla)</b> may be used analytically in cases where <b>causative</b> forms don't
        exist.</p>
    <x-vocabulary title="causative">
        <x-term :term="$terms->firstWhere('translit', 'xalla')"/>
    </x-vocabulary>
</div>

<div class="h-badge">
    <div class="badge h2">
        <div class="h">Syntax</div>
    </div>
    <div class="badge h2">
        <div class="h">Inflection</div>
    </div>
</div>

<p>Verbs follow <b>person agreement</b>, which by extension means they follow <b>gender & number agreement</b>.</p>
<p>Palestinian Arabic features two morphologically distinct tenses — the <b>Present Tense</b> & the <b>Past
        Tense</b> —
    & two morphologically distinct moods — the <b>Subjunctive</b> & the <b>Imperative</b>, in addition to the <b>Indicative</b>.
</p>

<div class="h-badge">
    <div class="badge h2">
        <div class="h">Syntax</div>
    </div>
    <div class="badge h2">
        <div class="h">Valence</div>
    </div>
</div>

<p>Arabic verbs fall into three broad types according to the number & type of arguments they take & the semantic
    role of
    the subject: <b class="orn">isPatient</b>, <b class="grn">noPatient</b>, & <b class="prp">hasObject</b> verbs,
    each
    of which is divided into sub-categories.</p>

<div class="box borderless grammar">
    <div class="h-badge">
        <div class="badge h1">
            <div class="h" style="text-transform: none">noPatient</div>
        </div>
    </div>
    <p><b><span class="grn">noPatient</span> verbs are terms that have no Patient</b>. Since <b
            class="grn">isPatient</b> verbs are intransitive (they have no Object), <b class="grn">Unergative</b>
        verbs
        refers specifically to unergative terms with no Object, which, by virtue of not being themselves the
        Patient,
        have no Patient at all. <b class="grn">Stative</b> verbs are the remaining intransitive terms that have no
        Agent
        (i.e. they are semantically adjectives).</p>

    <div class="inline-chart">
        <div class="chart-item">
            <div class="chart-title">Unergative</div>
            <div class="chart-subtitle">isAgent → !hasObject → !hasPatient</div>
        </div>
    </div>
    <x-sentence eng="he sat">
        <x-sentence-term arb="قعد" eng="ʔaʕad" :term="$terms->firstWhere('translit', 'ʔaʕad')"/>
    </x-sentence>
    <x-sentence eng="he told him">
        <x-sentence-term arb="حكا" eng="ħakā" :term="$terms->firstWhere('translit', 'ħaka')"/>
        <x-sentence-term arb="له" eng="-lo" :term="$terms->firstWhere('translit', 'la-')"/>
    </x-sentence>

    <div class="inline-chart">
        <div class="chart-item">
            <div class="chart-title">Stative&nbsp;&nbsp;&nbsp;</div>
            <div class="chart-subtitle">!isAgent → !hasObject → !hasPatient</div>
        </div>
    </div>

</div>
<div class="box borderless vocab">
    <div class="h-badge">
        <div class="badge h1">
            <div class="h" style="text-transform: none">hasObject</div>
        </div>
    </div>
    <p><b><span class="prp">hasObject</span> verbs are terms that have an Object</b>. Since all of these terms are
        transitive, the <b class="prp">Transitive</b> category refers to all transitive terms that don’t fall into
        any
        of the remaining categories. <b class="prp">Causative</b> terms are those whose Object is caused to carry
        out an
        intransitive action (its <b class="grn">Intransitive</b> counterpart). <b class="prp">Dative</b> terms are
        those
        whose Object is the dative-marked Object of an intransitive action (its <b class="grn">Intransitive</b>
        counterpart).</p>

    <div class="inline-chart">
        <div class="chart-item hasObject">
            <div class="chart-title">Transitive</div>
            <div class="chart-subtitle">isAgent → hasObject</div>
        </div>
    </div>
    <x-sentence eng="he hit him">
        <x-sentence-term arb="ضربـ" eng="ḍarab" :term="$terms->firstWhere('translit', 'ḍarab')"/>
        <x-sentence-term arb="ـه" eng="-o" :term="$terms->firstWhere('translit', '-o')"/>
    </x-sentence>

    <div class="inline-chart">
        <div class="chart-item hasObject">
            <div class="chart-title">Causative&nbsp;</div>
            <div class="chart-subtitle">isAgent → hasObject</div>
        </div>
    </div>
    <x-sentence eng="he made him sit">
        <x-sentence-term arb="قعّد" eng="ʔaʕʕad" :term="$terms->firstWhere('translit', 'ʔaʕʕad')"/>
        <x-sentence-term arb="ه" eng="-o" :term="$terms->firstWhere('translit', '-o')"/>
    </x-sentence>
    <x-sentence eng="he made him angry">
        <x-sentence-term arb="زعّلـ" eng="zaʕʕal" :term="$terms->firstWhere('translit', 'zaʕʕal')"/>
        <x-sentence-term arb="ـه" eng="-o" :term="$terms->firstWhere('translit', '-o')"/>
    </x-sentence>
    <div class="inline-chart">
        <div class="chart-item hasObject">
            <div class="chart-title">Dative&nbsp;&nbsp;&nbsp;&nbsp;</div>
            <div class="chart-subtitle">isAgent → hasObject</div>
        </div>
    </div>
    <x-sentence eng="he spoke to him">
        <x-sentence-term arb="حاكا" eng="ħākā" :term="$terms->firstWhere('translit', 'ħāka')"/>
        <x-sentence-term arb="ه" eng="-(h)" :term="$terms->firstWhere('translit', '-o')"/>
    </x-sentence>
</div>

<div class="box borderless dialog">
    <div class="h-badge">
        <div class="badge h1">
            <div class="h" style="text-transform: none">isPatient</div>
        </div>
    </div>
    <p><b><span class="orn">isPatient</span> verbs are terms whose grammatical Subject is the Patient of the action</b>.
        <b class="orn">Passive</b> verbs have a <b class="prp">Transitive</b> counterpart, whereas <b class="orn">Unaccusative</b>
        terms do not. The Subject of <b class="orn">Reflexive</b> & <b class="orn">Reciprocal</b> terms is the Agent
        itself; what distinguishes <b class="orn">Reciprocal</b> terms is that they have a <b class="prp">Dative</b>
        counterpart. Note that <b class="orn">Passive</b> verbs are also unaccusative, grammatically speaking; <b
            class="orn">Reflexive</b> & <b class="orn">Reciprocal</b> terms are not.</p>

    <div class="inline-chart">
        <div class="chart-item isPatient">
            <div class="chart-title">Unaccusative</div>
            <div class="chart-subtitle">!isAgent → !hasObject → isPatient → !isObject</div>
        </div>
    </div>
    <x-sentence eng="he became angry">
        <x-sentence-term arb="زعل" eng="ziʕil" :term="$terms->firstWhere('translit', 'ziʕil')"/>
    </x-sentence>

    <div class="inline-chart">
        <div class="chart-item isPatient">
            <div class="chart-title">Passive&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
            <div class="chart-subtitle">!isAgent → !hasObject → isPatient → isObject</div>
        </div>
    </div>
    <x-sentence eng="he was hit">
        <x-sentence-term arb="انضرب" eng="nḍarab" :term="$terms->firstWhere('translit', 'nḍarab')"/>
    </x-sentence>

    <div class="inline-chart">
        <div class="chart-item isPatient">
            <div class="chart-title">Reflexive&nbsp;&nbsp;&nbsp;</div>
            <div class="chart-subtitle">isAgent → !hasObject → isPatient → isObject</div>
        </div>
    </div>
    <x-sentence eng="he bathed (himself)">
        <x-sentence-term arb="تحمّم" eng="tħammam" :term="$terms->firstWhere('translit', 'tħammam')"/>
    </x-sentence>

    <div class="inline-chart">
        <div class="chart-item isPatient">
            <div class="chart-title">Reciprocal&nbsp;&nbsp;</div>
            <div class="chart-subtitle">isAgent → !hasObject → isPatient → isDative</div>
        </div>
    </div>
    <x-sentence eng="they fought (each other)">
        <x-sentence-term arb="تقاتلو" eng="tʔātalu" :term="$terms->firstWhere('translit', 'tʔātal')"/>
    </x-sentence>
</div>

<div class="inline-chart">
    <div class="chart-item hasObject">
        <div class="chart-title">Transitive</div>
        <div class="chart-subtitle">ضربو الشباب</div>
    </div>
    <div class="chart-item isPatient">
        <div class="chart-title">Passive</div>
        <div class="chart-subtitle">الشباب انضربو</div>
    </div>
</div>
<div class="inline-chart">
    <div class="chart-item hasObject">
        <div class="chart-title">Transitive</div>
        <div class="chart-subtitle">حمّم الولاد</div>
    </div>
    <div class="chart-item isPatient">
        <div class="chart-title">Reflexive</div>
        <div class="chart-subtitle">الولاد تحمّمو</div>
    </div>
</div>
<div class="inline-chart">
    <div class="chart-item">
        <div class="chart-title">Unergative</div>
        <div class="chart-subtitle">بحكيله</div>
    </div>
    <div class="chart-item hasObject">
        <div class="chart-title">Dative</div>
        <div class="chart-subtitle">بحاكيه</div>
    </div>
</div>
<div class="inline-chart">
    <div class="chart-item">
        <div class="chart-title">Unergative</div>
        <div class="chart-subtitle">شرب قهوة</div>
    </div>
    <div class="chart-item hasObject">
        <div class="chart-title">Causative</div>
        <div class="chart-subtitle">شرّبنا قهوة</div>
    </div>
</div>
