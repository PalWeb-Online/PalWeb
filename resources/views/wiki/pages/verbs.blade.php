<x-page-head title="{{ __('verbs') }}" blurb="Verbs are subject to person agreement with the sentence subject. They have tense & mood. Every
    sentence has one.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'syntax')">{{ __('syntax') }}</x-link>
    <x-link :href="route('wiki.show', 'verbs')">{{ __('verbs') }}</x-link>
</x-page-head>

<div id="copula" class="wiki-container">
    <h1>Copulae</h1>

    <h2>the Stative Copula</h2>

    <p>In Arabic, a <b>null copula</b> is used to define the state of a subject; this is the <b>stative
            copula</b>. Since the <b>stative copula</b> is not an overt verb, these sentences are usually referred to as
        <b>nominal sentences</b>, in contrast to <b>verbal sentences</b>. Because the <b>null copula</b> doesn't convey
        information about the grammatical person of the subject, an overt subject is required.</p>

    <div class="group-vertical">
        <x-sentence-item size="l" eng="the children are happy">
            <x-sentence-term arb="الولاد" eng="DEF-children" :term="$terms->firstWhere('translit', 'walad')"/>
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="مبسوطين" eng="P.happy" :term="$terms->firstWhere('translit', 'mabsūṭ')"/>
        </x-sentence-item>
        <x-sentence-item size="l" eng="they go">
            <x-sentence-term arb="بروحو" eng="3P.go" :term="$terms->firstWhere('translit', 'rāħ')"/>
        </x-sentence-item>
    </div>

    <p>However, because a <b>stative
            copula</b> may be modified by auxiliaries, <b>copulae</b> may actually be present in what appear to be <b>verbal
            sentences</b>. It is therefore better to distinguish all sentences with <b>copulae</b> as <b>copular
            sentences</b>; so-called <b>nominal sentences</b> are simply <b>copular sentences</b> that happen to be
        unmodified.</p>

    <div class="group-vertical">
        <x-sentence-item size="l" eng="they were happy">
            <x-sentence-term arb="كانو" eng="3P.were" :term="$terms->firstWhere('translit', 'kān')"/>
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="مبسوطين" eng="P.happy" :term="$terms->firstWhere('translit', 'mabsūṭ')"/>
        </x-sentence-item>
        <x-sentence-item size="l" eng="they were going">
            <x-sentence-term arb="كانو" eng="3P.were" :term="$terms->firstWhere('translit', 'kān')"/>
            <x-sentence-term arb="يروحو" eng="3P.go" :term="$terms->firstWhere('translit', 'rāħ')"/>
        </x-sentence-item>
    </div>

    <p>As in the foregoing sentence, the <b>stative copula</b> may be modified by an <b>aspectual verb</b> to indicate
        the stability of a state over time. While the unmarked <b>كان (kān)</b> indicates a static state, terms like <b>صار
            (ṣār)</b> & <b>بطّل (baṭṭal)</b> indicate the beginning & end of a state, respectively; other <b>aspectual
            verbs</b> exist as well. As usual, verbal auxiliaries agree with the subject.</p>

    <div class="group-vertical">
        <x-sentence-item size="l" eng="they became happy">
            <x-sentence-term arb="صارو" eng="3P.became" :term="$terms->firstWhere('translit', 'ṣār')"/>
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="مبسوطين" eng="P.happy" :term="$terms->firstWhere('translit', 'mabsūṭ')"/>
        </x-sentence-item>
        <x-sentence-item size="l" eng="they ceased being happy">
            <x-sentence-term arb="بطّلو" eng="3P.ceased" :term="$terms->firstWhere('translit', 'baṭṭal')"/>
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="مبسوطين" eng="P.happy" :term="$terms->firstWhere('translit', 'mabsūṭ')"/>
        </x-sentence-item>
    </div>

    <p>Negation always acts upon the first overt word in a verbal phrase, whether it be a verbal auxiliary or a verbal
        complement (see <b>the Existential Copula</b>) — skipping over the <b>null copula</b> if possible. If the verbal
        phrase only contains a <b>null copula</b>, the
        negative particle <b>مش (miš)</b> is produced.</p>

    <x-term-item :term="$terms->firstWhere('translit', 'miš')"/>

    <div class="group-vertical">
        <x-sentence-item size="l" eng="the children aren't happy">
            <x-sentence-term arb="الولاد" eng="DEF-children" :term="$terms->firstWhere('translit', 'walad')"/>
            <x-sentence-term arb="مش" eng="not" :term="$terms->firstWhere('translit', 'miš')"/>
            <x-sentence-term arb="مبسوطين" eng="P.happy" :term="$terms->firstWhere('translit', 'mabsūṭ')"/>
        </x-sentence-item>
        <x-sentence-item size="l" eng="they don't go">
            <x-sentence-term arb="مـ" eng="not" :term="$terms->firstWhere('translit', 'mā')"/>
            <x-sentence-term arb="بروحو" eng="3P.go" :term="$terms->firstWhere('translit', 'rāħ')"/>
            <x-sentence-term arb="ـش" eng="not" :term="$terms->firstWhere('translit', '-š')"/>
        </x-sentence-item>
        <x-sentence-item size="l" eng="they weren't happy">
            <x-sentence-term arb="مـ" eng="not" :term="$terms->firstWhere('translit', 'mā')"/>
            <x-sentence-term arb="كانو" eng="3P.were" :term="$terms->firstWhere('translit', 'kān')"/>
            <x-sentence-term arb="ـش" eng="not" :term="$terms->firstWhere('translit', '-š')"/>
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="مبسوطين" eng="P.happy" :term="$terms->firstWhere('translit', 'mabsūṭ')"/>
        </x-sentence-item>
        <x-sentence-item size="l" eng="they weren't going">
            <x-sentence-term arb="مـ" eng="not" :term="$terms->firstWhere('translit', 'mā')"/>
            <x-sentence-term arb="كانو" eng="3P.were" :term="$terms->firstWhere('translit', 'kān')"/>
            <x-sentence-term arb="ـش" eng="not" :term="$terms->firstWhere('translit', '-š')"/>
            <x-sentence-term arb="يروحو" eng="3P.go" :term="$terms->firstWhere('translit', 'rāħ')"/>
        </x-sentence-item>
    </div>

    <h2>the Existential Copula</h2>

    <p>An <b>existential copula</b> that indicates the existence of something may be formed by following a <b>null
            copula</b> with a <b>copular complement</b>, namely a preposition with a definite, cliticized referent.</p>
    <p>In Palestinian Arabic, the default <b>copular complement</b> is the expletive <b>فيه (fīh)</b>, a frozen particle
        that is never inflected. Etymologically meaning <b>"in-it"</b>, its function is similar to <b>"there"</b> in the
        English <b>"there is"</b>.</p>

    <div class="group-vertical">
        <x-sentence-item size="l" eng="there is cheese">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
            <x-sentence-term eng="cheese" :term="$terms->firstWhere('slug', 'noun-žibne')"/>
        </x-sentence-item>
    </div>

    <x-term-item :term="$terms->firstWhere('translit', 'fīh')"/>

    <p>While the use of the expletive is not too dissimilar from English, a handful of true prepositions may act as <b>copular
            complements</b> as well, providing an existential meaning without the need for an expletive.</p>

    <div class="group-horizontal">
        <x-sentence-item size="l" eng="there is cheese on it">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="عليها" eng="on-it.F" :term="$terms->firstWhere('slug', 'preposition-ʕala')"/>
            <x-sentence-term eng="cheese" :term="$terms->firstWhere('slug', 'noun-žibne')"/>
        </x-sentence-item>
    </div>

    <p>If the same prepositional phrase were to appear after the noun, however, it would not be a <b>copular
            complement</b>; the expletive would be necessary to create the existential meaning.</p>

    <div class="group-vertical">
        <x-sentence-item size="l" eng="??? cheese on it">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term eng="cheese" :term="$terms->firstWhere('slug', 'noun-žibne')"/>
            <x-sentence-term arb="عليها" eng="on-it.F" :term="$terms->firstWhere('slug', 'preposition-ʕala')"/>
        </x-sentence-item>
        <x-sentence-item size="l" eng="there is cheese on it">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
            <x-sentence-term eng="cheese" :term="$terms->firstWhere('slug', 'noun-žibne')"/>
            <x-sentence-term arb="عليها" eng="on-it.F" :term="$terms->firstWhere('slug', 'preposition-ʕala')"/>
        </x-sentence-item>
    </div>

    <p>As mentioned above, a <b>copular complement</b> must have a cliticized referent; it cannot be a standalone noun.
    </p>

    <div class="group-vertical">
        <x-sentence-item size="l" eng="there is cheese on the salad">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
            <x-sentence-term eng="cheese" :term="$terms->firstWhere('slug', 'noun-žibne')"/>
            <x-sentence-term arb="على" eng="on" :term="$terms->firstWhere('slug', 'preposition-ʕala')"/>
            <x-sentence-term arb="السلطة" eng="DEF-salad" :term="$terms->firstWhere('slug', 'noun-salaṭa')"/>
        </x-sentence-item>
        <x-sentence-item size="l" eng="??? on the salad cheese">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="على" eng="on" :term="$terms->firstWhere('slug', 'preposition-ʕala')"/>
            <x-sentence-term arb="السلطة" eng="DEF-salad" :term="$terms->firstWhere('slug', 'noun-salaṭa')"/>
            <x-sentence-term eng="cheese" :term="$terms->firstWhere('slug', 'noun-žibne')"/>
        </x-sentence-item>
    </div>

    <p>When a <b>copular complement</b> other than the expletive is used, the topic may be definite. This, however,
        rules out purely existential interpretations in favor of possessive ones.</p>

    <div class="group-vertical">
        <x-sentence-item size="l" eng="there is a book / it has a book (in it)">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term eng="(there) / in-it.M" :term="$terms->firstWhere('translit', 'fīh')"/>
            <x-sentence-term eng="book" :term="$terms->firstWhere('slug', 'noun-ktāb')"/>
        </x-sentence-item>
        <x-sentence-item size="l" eng="it has the book (in it)">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term eng="in-it.M" :term="$terms->firstWhere('translit', 'fīh')"/>
            <x-sentence-term arb="الكتاب" eng="DEF-book" :term="$terms->firstWhere('slug', 'noun-ktāb')"/>
        </x-sentence-item>
    </div>

    <p>In theory, there is no limit to the amount of <b>copular complements</b> in an <b>existential copula</b>,
        although in most cases having multiple referents would be nonsensical. However, the expletive <b>فيه (fīh)</b> —
        being unspecific about its referent — may always be used in conjunction with other <b>copular complements</b>.
        While it does not add any meaning, it underlines the indefiniteness of the topic; indeed, it may only be used if
        the topic is indefinite.</p>

    <div class="group-vertical">
        <x-sentence-item size="l" eng="there is cheese on it">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
            <x-sentence-term arb="عليها" eng="on-it.F" :term="$terms->firstWhere('slug', 'preposition-ʕala')"/>
            <x-sentence-term eng="cheese" :term="$terms->firstWhere('slug', 'noun-žibne')"/>
        </x-sentence-item>
    </div>

    <div class="group-vertical">
        <x-sentence-item size="l" eng="the cheese is on it">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="عليها" eng="on-it.F" :term="$terms->firstWhere('slug', 'preposition-ʕala')"/>
            <x-sentence-term arb="الجبنة" eng="DEF-cheese" :term="$terms->firstWhere('slug', 'noun-žibne')"/>
        </x-sentence-item>

        <x-sentence-item size="l" eng="??? there is the cheese on it">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
            <x-sentence-term arb="عليها" eng="on-it.F" :term="$terms->firstWhere('slug', 'preposition-ʕala')"/>
            <x-sentence-term arb="الجبنة" eng="DEF-cheese" :term="$terms->firstWhere('slug', 'noun-žibne')"/>
        </x-sentence-item>
    </div>

    <p>Because an <b>existential copula</b> is fundamentally an impersonal verb that has no subject but rather a
        referent that is necessarily undefined, verbal auxiliaries that modify it are never inflected.</p>

    <div class="group-vertical">
        <x-sentence-item size="l" eng="there was cheese">
            <x-sentence-term arb="كان" eng="was" :term="$terms->firstWhere('translit', 'kān')"/>
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
            <x-sentence-term eng="cheese" :term="$terms->firstWhere('slug', 'noun-žibne')"/>
        </x-sentence-item>
        <x-sentence-item size="l" eng="there was cheese on it">
            <x-sentence-term arb="كان" eng="was" :term="$terms->firstWhere('translit', 'kān')"/>
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="عليها" eng="on-it.F" :term="$terms->firstWhere('slug', 'preposition-ʕala')"/>
            <x-sentence-term eng="cheese" :term="$terms->firstWhere('slug', 'noun-žibne')"/>
        </x-sentence-item>
    </div>

    <p>Aside from <b>فيه (fīh)</b>, the remaining <b>copular complements</b> convey more or less possessive meanings by
        situating the existence of something at the possessor's location.</p>

    <div class="group-vertical">
        <x-sentence-item size="l" eng="she has work">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="عندها" eng="at-her" :term="$terms->firstWhere('slug', 'preposition-ʕind')"/>
            <x-sentence-term eng="work" :term="$terms->firstWhere('slug', 'noun-šuġl')"/>
        </x-sentence-item>
        <x-sentence-item size="l" eng="she owns a car">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="إلها" eng="to-her" :term="$terms->firstWhere('slug', 'preposition-la-')"/>
            <x-sentence-term eng="car" :term="$terms->firstWhere('slug', 'noun-sayyāra')"/>
        </x-sentence-item>
        <x-sentence-item size="l" eng="she has the keys with her">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="معها" eng="with-her" :term="$terms->firstWhere('slug', 'preposition-maʕ')"/>
            <x-sentence-term arb="المفاتيح" eng="DEF-keys" :term="$terms->firstWhere('slug', 'noun-sayyāra')"/>
        </x-sentence-item>
    </div>

    <div class="terms-list">
        <x-term-item :term="$terms->firstWhere('translit', 'ʕindo')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔilo')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'maʕo')"/>
    </div>

    <p>Additional idiomatic verbs may be built from these <b>copular complements</b>.</p>

    <div class="group-vertical">
        <x-sentence-item size="l" eng="she owes me money">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="إلي عليها" eng="to-me on-her" :term="$terms->firstWhere('slug', 'verb-ʔilo ʕalēh')"/>
            <x-sentence-term eng="money" :term="$terms->firstWhere('translit', 'maṣāri')"/>
        </x-sentence-item>
    </div>

    <x-term-item :term="$terms->firstWhere('translit', 'ʔilo ʕalēh')"/>

    <p>Sometimes, the <b>existential copula</b> may be preceded by what appears to be a subject: the semantic possessor.
        However, the subject is actually undefined; the preceding noun is a <b>topic</b>. We see this, firstly, in that
        this false subject may never succeed the verb — contrary to the norm in an SVO language like Arabic.</p>

    <div class="group-vertical">
        <x-sentence-item size="l" eng="Maryam has work">
            <x-sentence-term arb="مريم" eng="Maryam"/>
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="عندها" eng="at-her" :term="$terms->firstWhere('translit', 'ʔind')"/>
            <x-sentence-term eng="work" :term="$terms->firstWhere('slug', 'noun-šuġl')"/>
        </x-sentence-item>
        <x-sentence-item size="l" eng="??? she has Maryam work">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="عندها" eng="at-her" :term="$terms->firstWhere('translit', 'ʔind')"/>
            <x-sentence-term arb="مريم" eng="Maryam"/>
            <x-sentence-term eng="work" :term="$terms->firstWhere('slug', 'noun-šuġl')"/>
        </x-sentence-item>
    </div>

    <p>Secondly, in that verbal auxiliaries are never inflected according to this false subject.</p>

    <div class="group-vertical">
        <x-sentence-item size="l" eng="the salad had cheese on it">
            <x-sentence-term arb="السلطة" eng="DEF-salad" :term="$terms->firstWhere('slug', 'noun-salaṭa')"/>
            <x-sentence-term arb="كان" eng="was" :term="$terms->firstWhere('translit', 'kān')"/>
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="عليها" eng="on-it.F" :term="$terms->firstWhere('slug', 'preposition-ʕala')"/>
            <x-sentence-term eng="cheese" :term="$terms->firstWhere('slug', 'noun-žibne')"/>
        </x-sentence-item>
        <x-sentence-item size="l" eng="the bag had a banana in it">
            <x-sentence-term arb="الشنتة" eng="DEF-bag" :term="$terms->firstWhere('translit', 'šanta')"/>
            <x-sentence-term arb="كان" eng="was" :term="$terms->firstWhere('translit', 'kān')"/>
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="فيها" eng="in-it.F" :term="$terms->firstWhere('translit', 'fīh')"/>
            <x-sentence-term arb="موزة" eng="banana" :term="$terms->firstWhere('slug', 'noun-mōz')"/>
        </x-sentence-item>
    </div>

    <x-term-item :term="$terms->firstWhere('translit', 'fiyyo')"/>
    <x-term-item :term="$terms->firstWhere('translit', 'ʕalēh')"/>

    <p>When true prepositions act as <b>copular complements</b> — especially without the expletive — they function
        very similarly to true <b>pseudo-verbs</b>. Because <b>copular complements</b> must have a definite referent in
        the form of a clitic, they appear as though always conjugated. Negation may act upon them. Moreover, their
        possessive meanings make them semantically like transitive verbs. Although these <b>copular complements</b> are
        categorized in the <b>Dictionary</b> as <b>pseudo-verbs</b> for the sake of practicality, they are a distinct
        category of terms.</p>

    <div class="group-vertical">
        <x-sentence-item size="l" eng="she owes me $50">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
            <x-sentence-term arb="إلي" eng="to-me" :term="$terms->firstWhere('slug', 'preposition-la-')"/>
            <x-sentence-term arb="عليها" eng="on-her" :term="$terms->firstWhere('slug', 'preposition-ʕala')"/>
            <x-sentence-term eng="fifty" :term="$terms->firstWhere('slug', 'numeral-xamsīn')"/>
            <x-sentence-term eng="dollar" :term="$terms->firstWhere('slug', 'noun-dōlār')"/>
        </x-sentence-item>
    </div>

    <p>Negation always acts upon the first — & only the first — overt word in a verbal phrase, whether it be a verbal
        auxiliary or a verbal complement. When the <b>particle</b> <b>فيه (fīh)</b> is negated, it undergoes a sound
        change that shortens its phonemically long final vowel. Likewise, <b>إله (ʔilo)</b> loses its initial glottal
        stop.</p>

    <div class="group-vertical">
        <x-sentence-item size="l" eng="she does not owe money">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="مـ ـعليها ش" eng="not on-her not"
                             :term="$terms->firstWhere('slug', 'preposition-ʕala')"/>
            <x-sentence-term eng="money" :term="$terms->firstWhere('translit', 'maṣāri')"/>
        </x-sentence-item>
        <x-sentence-item size="l" eng="she does not owe me money">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="مـ ـليـ ـش" eng="not to-me not"
                             :term="$terms->firstWhere('slug', 'preposition-la-')"/>
            <x-sentence-term arb="عليها" eng="on-her" :term="$terms->firstWhere('slug', 'preposition-ʕala')"/>
            <x-sentence-term eng="money" :term="$terms->firstWhere('translit', 'maṣāri')"/>
        </x-sentence-item>
        <x-sentence-item size="l" eng="she does not owe me money">
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="مـ ـفـ ـش" eng="not (there) not" :term="$terms->firstWhere('translit', 'fīh')"/>
            <x-sentence-term arb="إلي" eng="to-me" :term="$terms->firstWhere('slug', 'preposition-la-')"/>
            <x-sentence-term arb="عليها" eng="on-her" :term="$terms->firstWhere('slug', 'preposition-ʕala')"/>
            <x-sentence-term eng="money" :term="$terms->firstWhere('translit', 'maṣāri')"/>
        </x-sentence-item>
        <x-sentence-item size="l" eng="she did not owe me money">
            <x-sentence-term arb="مـ ـكانـ ـش" eng="not was not" :term="$terms->firstWhere('translit', 'kān')"/>
            <x-sentence-term arb="∅" eng="(be)"/>
            <x-sentence-term arb="فيه" eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
            <x-sentence-term arb="إلي" eng="to-me" :term="$terms->firstWhere('slug', 'preposition-la-')"/>
            <x-sentence-term arb="عليها" eng="on-her" :term="$terms->firstWhere('slug', 'preposition-ʕala')"/>
            <x-sentence-term eng="money" :term="$terms->firstWhere('translit', 'maṣāri')"/>
        </x-sentence-item>
    </div>
</div>

<div id="pseudo" class="wiki-container">
    <h1>pseudo-verbs</h1>
    <p>Alongside etymological verbs, a few other terms in Palestinian Arabic have been grammaticalized as verbs. What
        these <b>pseudo-verbs</b> have in common
        is that — unlike etymological verbs — they are conjugated using <b>clitic pronouns</b> rather
        than by way of inflectional morphology.</p>

    <p>It should be noted that <b>verbal prepositions</b> are categorized here as <b>pseudo-verbs</b>. However, at heart
        they are prepositions used to form <b>possessive
            copulae</b>, which are only reanalyzed as <b>pseudo-verbs</b> when all other elements of the underlying
        structure are null; they are therefore subject to major restrictions as <b>pseudo-verbs</b>, including the fact
        that they cannot be directly modified by auxiliaries.</p>

    <p>In general, the grammaticalization of <b>pseudo-verbs</b> is visible in that they may be modified by auxiliaries
        that agree with the semantic agent rather than with the <b>pseudo-verb</b> itself.</p>

    <x-sentence-item eng="Maryam was speaking">
        <x-sentence-term arb="مريم" eng="Maryam"/>
        <x-sentence-term arb="كانت" eng="3F.was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="تحكي" eng="3F.speaks" :term="$terms->firstWhere('translit', 'ħaka')"/>
    </x-sentence-item>

    <x-sentence-item eng="Maryam wanted water">
        <x-sentence-term arb="مريم" eng="Maryam"/>
        <x-sentence-term arb="كانت" eng="3F.was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="بدّها" eng="3F.want" :term="$terms->firstWhere('translit', 'bidd-')"/>
        <x-sentence-term arb="ميّ" eng="water" :term="$terms->firstWhere('translit', 'mayy')"/>
    </x-sentence-item>

    <x-sentence-item eng="Maryam seemed upset">
        <x-sentence-term arb="مريم" eng="Maryam"/>
        <x-sentence-term arb="كانت" eng="3F.was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="شكلها" eng="3F.seems" :term="$terms->firstWhere('translit', 'šikl-')"/>
        <x-sentence-term arb="زعلانة" eng="F.upset" :term="$terms->firstWhere('translit', 'zaʕlān')"/>
    </x-sentence-item>

    <p>Having said that, the fact that auxiliaries may be used uninflected is a testament to the origin of <b>pseudo-verbs</b>
        & evidence of their incomplete grammaticalization.</p>

    <x-sentence-item eng="Maryam wanted water">
        <x-sentence-term arb="مريم" eng="Maryam"/>
        <x-sentence-term arb="كان" eng="was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="بدّها" eng="3F.want" :term="$terms->firstWhere('translit', 'bidd-')"/>
        <x-sentence-term arb="ميّ" eng="water" :term="$terms->firstWhere('translit', 'mayy')"/>
    </x-sentence-item>

    <x-sentence-item eng="Maryam seemed upset">
        <x-sentence-term arb="مريم" eng="Maryam"/>
        <x-sentence-term arb="كان" eng="was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="شكلها" eng="3F.seems" :term="$terms->firstWhere('translit', 'šikl-')"/>
        <x-sentence-term arb="زعلانة" eng="F.upset" :term="$terms->firstWhere('translit', 'zaʕlān')"/>
    </x-sentence-item>

    <p>Similarly, <b>verbal prepositions</b> must be modified by an uninflected auxiliary due to the underlying syntax
        of the <b>possessive copula</b>; here, they are not <b>pseudo-verbs</b>.</p>

    <x-sentence-item eng="Maryam had a car">
        <x-sentence-term arb="مريم" eng="Maryam"/>
        <x-sentence-term arb="كان" eng="was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="عندها" eng="3F.has" :term="$terms->firstWhere('translit', 'ʕind-')"/>
        <x-sentence-term arb="سيّارة" eng="a car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    </x-sentence-item>

    <p>With the single exception of <b>شكله (šiklo)</b>, all <b>pseudo-verbs</b> may be directly negated. However, <b>verbal
            prepositions</b> in the <b>possessive copula</b> are not; instead, negation attaches to the head of the
        highest verbal phrase.</p>

    <x-sentence-item eng="I don't want it">
        <x-sentence-term arb="مـ" eng="not" :term="$terms->firstWhere('translit', 'mā')"/>
        <x-sentence-term arb="بدّي" eng="1S.want" :term="$terms->firstWhere('translit', 'bidd-')"/>
        <x-sentence-term arb="ـش" eng="not" :term="$terms->firstWhere('translit', 'š')"/>
        <x-sentence-term arb="ايّاه" eng="M.it" :term="$terms->firstWhere('translit', 'yyā-')"/>
    </x-sentence-item>

    <x-sentence-item eng="I don't have a car">
        <x-sentence-term arb="مـ" eng="not" :term="$terms->firstWhere('translit', 'mā')"/>
        <x-sentence-term arb="عندي" eng="1S.have" :term="$terms->firstWhere('translit', 'ʕind-')"/>
        <x-sentence-term arb="ـش" eng="not" :term="$terms->firstWhere('translit', 'š')"/>
        <x-sentence-term arb="سيّارة" eng="a car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    </x-sentence-item>

    <x-sentence-item eng="I don't have a car">
        <x-sentence-term arb="مـ" eng="not" :term="$terms->firstWhere('translit', 'mā')"/>
        <x-sentence-term arb="∅" eng="(be)"/>
        <x-sentence-term arb="فيه" eng="(there)" :term="$terms->firstWhere('translit', 'fīh')"/>
        <x-sentence-term arb="ـش" eng="not" :term="$terms->firstWhere('translit', '-š')"/>
        <x-sentence-term arb="عندي" eng="1S.have" :term="$terms->firstWhere('translit', 'ʕind-')"/>
        <x-sentence-term arb="سيّارة" eng="a car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    </x-sentence-item>

    <p>With regard to <b>بدّه (biddo)</b>, it is a transitive verb that requires an object. Note that the interference
        of the
        <b>clitic pronoun</b> always forces the direct object onto the <b>ايّا (yyā-)</b> affix.</p>

    <x-sentence-item eng="I want it">
        <x-sentence-term arb="بدّي" eng="1S.want" :term="$terms->firstWhere('translit', 'bidd-')"/>
        <x-sentence-term arb="ايّاه" eng="M.it" :term="$terms->firstWhere('translit', 'yyā-')"/>
    </x-sentence-item>

    <p>With regard to <b>شكله (šiklo "to seem")</b>, it is a raising verb that raises a constituent to the subject
        position; if clause-initial, it may refer to a null
        subject as well.</p>
    <x-sentence-item eng="the car seems new">
        <x-sentence-term arb="السيّارة" eng="the-car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
        <x-sentence-term arb="شكلها" eng="3F.seems" :term="$terms->firstWhere('translit', 'šikl-')"/>
        <x-sentence-term arb="جديدة" eng="F.new" :term="$terms->firstWhere('translit', 'jdīd')"/>
    </x-sentence-item>
    <x-sentence-item eng="it seems the car is new">
        <x-sentence-term arb="شكله" eng="3M.seems" :term="$terms->firstWhere('translit', 'šikl-')"/>
        <x-sentence-term arb="السيّارة" eng="the-car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
        <x-sentence-term arb="جديدة" eng="F.new" :term="$terms->firstWhere('translit', 'jdīd')"/>
    </x-sentence-item>
    <p>In the case of the null-subject construction, the tense of the predicate is flexible. However, the tense
        of <b>شكله (šiklo)</b> itself applies to the entire subordinate clause, so the double-marking of tense would be
        perceived as either redundant or semantically confusing.</p>
    <x-sentence-item eng="it seems the car was new">
        <x-sentence-term arb="شكله" eng="3M.seems" :term="$terms->firstWhere('translit', 'šikl-')"/>
        <x-sentence-term arb="السيّارة" eng="the-car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
        <x-sentence-term arb="كانت" eng="3F.was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="جديدة" eng="F.new" :term="$terms->firstWhere('translit', 'jdīd')"/>
    </x-sentence-item>
    <x-sentence-item eng="it seemed the car was new">
        <x-sentence-term arb="كان" eng="was" :term="$terms->firstWhere('translit', 'kān')"/>
        <x-sentence-term arb="شكله" eng="3M.seems" :term="$terms->firstWhere('translit', 'šikl-')"/>
        <x-sentence-term arb="السيّارة" eng="the-car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
        <x-sentence-term arb="جديدة" eng="F.new" :term="$terms->firstWhere('translit', 'jdīd')"/>
    </x-sentence-item>
    <x-vocabulary title="pseudo-verbs">
        <x-term-item :term="$terms->firstWhere('translit', 'biddo')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'šiklo')"/>
    </x-vocabulary>
</div>

<div class="wiki-container">
    <h1>auxiliaries</h1>
    <x-vocabulary title="aspectual">
        <x-term-item :term="$terms->firstWhere('translit', 'kān')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ṣār')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'baṭṭal')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ḍall')"/>
        <x-term-item :term="$terms->firstWhere('translit', 'ʔaʕad')"/>
    </x-vocabulary>
    <p>In Arabic, many intransitive verbs have <b>causative</b> counterparts. However, the <b>causative
            auxiliary</b> <b>خلّى (xalla)</b> may be used analytically in cases where <b>causative</b> forms don't
        exist.</p>
    <x-vocabulary title="causative">
        <x-term-item :term="$terms->firstWhere('translit', 'xalla')"/>
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
    <x-sentence-item eng="he sat">
        <x-sentence-term arb="قعد" eng="ʔaʕad" :term="$terms->firstWhere('translit', 'ʔaʕad')"/>
    </x-sentence-item>
    <x-sentence-item eng="he told him">
        <x-sentence-term arb="حكا" eng="ħakā" :term="$terms->firstWhere('translit', 'ħaka')"/>
        <x-sentence-term arb="له" eng="-lo" :term="$terms->firstWhere('translit', 'la-')"/>
    </x-sentence-item>

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
    <x-sentence-item eng="he hit him">
        <x-sentence-term arb="ضربـ" eng="ḍarab" :term="$terms->firstWhere('translit', 'ḍarab')"/>
        <x-sentence-term arb="ـه" eng="-o" :term="$terms->firstWhere('translit', '-o')"/>
    </x-sentence-item>

    <div class="inline-chart">
        <div class="chart-item hasObject">
            <div class="chart-title">Causative&nbsp;</div>
            <div class="chart-subtitle">isAgent → hasObject</div>
        </div>
    </div>
    <x-sentence-item eng="he made him sit">
        <x-sentence-term arb="قعّد" eng="ʔaʕʕad" :term="$terms->firstWhere('translit', 'ʔaʕʕad')"/>
        <x-sentence-term arb="ه" eng="-o" :term="$terms->firstWhere('translit', '-o')"/>
    </x-sentence-item>
    <x-sentence-item eng="he made him angry">
        <x-sentence-term arb="زعّلـ" eng="zaʕʕal" :term="$terms->firstWhere('translit', 'zaʕʕal')"/>
        <x-sentence-term arb="ـه" eng="-o" :term="$terms->firstWhere('translit', '-o')"/>
    </x-sentence-item>
    <div class="inline-chart">
        <div class="chart-item hasObject">
            <div class="chart-title">Dative&nbsp;&nbsp;&nbsp;&nbsp;</div>
            <div class="chart-subtitle">isAgent → hasObject</div>
        </div>
    </div>
    <x-sentence-item eng="he spoke to him">
        <x-sentence-term arb="حاكا" eng="ħākā" :term="$terms->firstWhere('translit', 'ħāka')"/>
        <x-sentence-term arb="ه" eng="-(h)" :term="$terms->firstWhere('translit', '-o')"/>
    </x-sentence-item>
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
    <x-sentence-item eng="he became angry">
        <x-sentence-term arb="زعل" eng="ziʕil" :term="$terms->firstWhere('translit', 'ziʕil')"/>
    </x-sentence-item>

    <div class="inline-chart">
        <div class="chart-item isPatient">
            <div class="chart-title">Passive&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
            <div class="chart-subtitle">!isAgent → !hasObject → isPatient → isObject</div>
        </div>
    </div>
    <x-sentence-item eng="he was hit">
        <x-sentence-term arb="انضرب" eng="nḍarab" :term="$terms->firstWhere('translit', 'nḍarab')"/>
    </x-sentence-item>

    <div class="inline-chart">
        <div class="chart-item isPatient">
            <div class="chart-title">Reflexive&nbsp;&nbsp;&nbsp;</div>
            <div class="chart-subtitle">isAgent → !hasObject → isPatient → isObject</div>
        </div>
    </div>
    <x-sentence-item eng="he bathed (himself)">
        <x-sentence-term arb="تحمّم" eng="tħammam" :term="$terms->firstWhere('translit', 'tħammam')"/>
    </x-sentence-item>

    <div class="inline-chart">
        <div class="chart-item isPatient">
            <div class="chart-title">Reciprocal&nbsp;&nbsp;</div>
            <div class="chart-subtitle">isAgent → !hasObject → isPatient → isDative</div>
        </div>
    </div>
    <x-sentence-item eng="they fought (each other)">
        <x-sentence-term arb="تقاتلو" eng="tʔātalu" :term="$terms->firstWhere('translit', 'tʔātal')"/>
    </x-sentence-item>
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
