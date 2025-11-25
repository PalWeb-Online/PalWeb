<script setup>
import {route} from "ziggy-js";
import Wiki from "../Wiki.vue";
import Layout from "../../../Shared/Layout.vue";

defineOptions({
    layout: [Layout, Wiki],
})
</script>
<template>
    <div class="wiki-content-block">
<!--        <p>what about non-inflectional affixes like relative adjectives? i guess that can just be listed in the adjectives page</p>-->
        <h1>Inflections</h1>
        <h2>Introduction</h2>
        <p>In Arabic, there are four types of grammatical properties for which terms are inflected: gender, number &
            definiteness, which apply to nouns & adjectives, as well as person, applying to verbs. The values these
            properties can have are: for gender, masculine or feminine; for number, singular or plural; for
            definiteness, definite or indefinite; for person, there are eight grammatical persons that sometimes also
            contain gender & number information. It should be noted that, in terms of syntax, gender & number are
            collapsed into a single system with three possible values — masculine, feminine & plural, with the plural
            being gender-neutral.</p>
        <p>Generally speaking, inflections in Arabic are formed through affixation, with the notable exception of <b>broken
            plurals</b>. We saw in
            <Link :href="route('wiki.show', 'patterns')">Morphology: Patterns</Link>
            that
            most nouns & some adjectives have <b>broken plurals</b>, meaning their plural forms are derived by placing
            the term's root in a plural pattern that is usually drawn from a pool of patterns associated with terms of a
            given length. Verbal inflections are forced through affixation, but there are additional morphological
            changes that will be discussed here as well.
        </p>

        <h2>Nouns & Adjectives</h2>
        <h3>Gender</h3>
        <p>In Arabic, there are two grammatical genders: masculine & feminine, with the masculine being unmarked.
            Among adjectives, the uninflected form (i.e. the dictionary or lemma form) is the masculine form, while the
            feminine form is always formed with <b>ة (-e)</b>, except for Defect Adjectives (see <b>Syntax:
                Adjectives</b>).
            Nouns have a inherent gender that is invariable & can usually be identified by the presence or absence of
            <b>ة (-e)</b>, although there are exceptions. Only some human nouns have distinct masculine &
            feminine forms, distinguished by <b>ة (-e)</b>.</p>

        <ul>
            <li>Adjectives</li>
            <ul>
                <li><b>سهل (sihil)</b> > <b>سهلة (sihle)</b></li>
                <li><b>لطيف (laṭīf)</b> > <b>لطيفة (latīfe)</b></li>
                <li><b>جدّيّ (žaddi)</b> > <b>جدّيّة (žaddiyye)</b></li>
            </ul>

            <li>Nouns</li>
            <ul>
                <li>Masculine</li>
                <ul>
                    <li><b>مفتاح (muftāħ)</b></li>
                    <li><b>سمك (samak)</b></li>
                    <li><b>دكتور (duktōr)</b></li>
                    <li><b>زلمة (zalame)</b></li>
                </ul>
                <li>Feminine</li>
                <ul>
                    <li><b>غرفة (ġurfe)</b></li>
                    <li><b>تفّاحة (tuffāħa)</b></li>
                    <li><b>دكتورة (duktōra)</b></li>
                    <li><b>شمس (šams)</b></li>
                </ul>
            </ul>
        </ul>

        <h3>Number</h3>
        <p>In Spoken Arabic, there are two categories of grammatical number: singular & plural; the dual category,
            present in Standard Arabic, has been lost as a distinct syntactic category. Although the <b>ين (-ēn)</b>
            dual suffix remains as the default way to indicate pairs of nouns, those pairs are always syntactically
            plural. Since there is no dual agreement, the dual suffix has no use among adjectives.</p>
        <ul>
            <li><b>شخص (šaxṣ, "(one) person")</b> > <b>شخصين (šaxṣēn, "two people")</b></li>
            <li><b>جهة (žiha, "(one) side")</b> > <b>جهتين (žihtēn, "two sides")</b></li>
        </ul>
        <p>Pluralization through suffixation produces what are called <b>sound plurals</b>, in contrast to <b>broken
            plurals</b>, which are based on patterns. (See
            <Link :href="route('wiki.show', 'patterns')">Morphology: Patterns</Link>
            for more information on what determines which type of plural a term has.)
        </p>
        <p>There are two pluralization suffixes: <b>ين (-īn)</b> & <b>ات (-āt)</b>. We will refer to these as the
            <b>masculine plural suffix</b> & <b>feminine plural suffix</b>, respectively, because these are their
            etymological origins, with <b>ات (-āt)</b> being related to <b>ة (-e)</b>. Historically in Arabic the
            masculine-feminine gender distinction obtains in the plural; that is, masculine plural & feminine plural
            are distinct syntactic categories with their own inflectional behavior, as is still the case in Standard
            Arabic. However, this syntactic distinction has been lost in Spoken Arabic, with the masculine plural, which
            was used in reference to mixed groups, becoming gender-neutral. As a result, the use of <b>ين (-īn)</b> has
            become universal for adjectives. Although <b>ات (-āt)</b> is still available to pluralize groups of
            exclusively feminine animate nouns – usually humans – & their adjectives, doing so is uncommon.</p>
        <p>Both suffixes are used, however, among plural nouns, whose plural inflections have become lexicalized. In
            theory, the suffix used corresponds to the gender of the noun in question, but two factors make it appear
            on the surface as a question of animacy: Because inanimate plurals in Arabic are treated distinctly as
            masses that are grammatically feminine, they are always pluralized with <b>ات (-āt)</b>. As for animate
            plurals, due to the loss of the feminine plural category in Spoken Arabic, the default, gender-neutral
            plural formed with <b>ين (-īn)</b> is used even when referring to a group of exclusively femalinine animate
            nouns, even if <b>ات (-āt)</b> is still possible.</p>
        <ul>
            <li>Adjectives</li>
            <ul>
                <li><b>سهل (sihil)</b> > <b>سهلين (sihlīn)</b></li>
                <li><b>لطيف (laṭīf)</b> > <b>لطيفين (latīfīn)</b></li>
                <li><b>جدّيّ (žaddi)</b> > <b>جدّيّين (žaddiyyīn)</b></li>
            </ul>

            <li>Nouns</li>
            <ul>
                <li><b>طبّاخ (ṭabbāx, "chef")</b> > <b>طبّاخين (ṭabbāxīn, "chefs")</b></li>
                <li><b>مستأجر (mustaʔžir, "tenant")</b> > <b>مستأجرين (mustaʔžirīn, "tenants")</b></li>
                <li><b>مكتبة (maktabe, "library")</b> > <b>مكتبات (maktabāt, "libraries")</b></li>
                <li><b>واجب (wāžib, "duty")</b> > <b>واجبات (wāžibāt, "duties")</b></li>
            </ul>
        </ul>

        <h3>Definiteness</h3>
        <p>Arabic distinguishes between definite & indefinite nouns, with adjectives being subjected to definiteness
            agreement with the noun. The unmarked, lemma form of nouns & adjectives is indefinite — with the obvious
            exception of proper nouns, which are inherently definite. Definiteness in nouns & adjectives is marked by
            the prefix <b>الـ (l-)</b>, which may be realized as <b>[(ʔ)il]</b>, <b>[li]</b> or just <b>[l]</b>
            depending on the surrounding environment & the speaker (see <b>Phonology: Vowels</b>).</p>
        <p>In some cases, the definiteness of the noun may be contained within a particular grammatical structure, like
            the compound noun structure (<b>ʔiḍāfa</b>); in these cases, a noun may be definite despite not presenting
            the <b>الـ (l-)</b> prefix. (See <b>Syntax: Nouns</b> for the rules pertaining to the formation of such
            structures & the role of the <b>الـ (l-)</b> prefix within them.) As for adjectives, they are only ever
            marked as definite through prefixation.</p>
        <ul>
            <li><b>فتّاحة قناني زغير (fattāħt ʔanāny ẓġīr, "a small bottle opener")</b></li>
            <li><b>فتّاحة القناني الزغير (fattāħt l-ʔanāny l-ẓġīr, "the small bottle opener")</b></li>
        </ul>

        <h2>Verbs</h2>
        <p>While the landscape of
            <Link :href="route('wiki.show', 'patterns') + '#verbal'">Verb Forms</Link>
            may seem complex, verbal patterns do not affect conjugation in & of
            themselves. Conjugation is based on inflectional prefixes & suffixes that are always & completely regular;
            these affixes are attached to stems that are used to build the conjugations of the different tenses & moods.
            It is these stems that are subject to some irregularity in their use & derivation, although not on the basis
            of their patterning but rather their root.
        </p>
        <p>As previously mentioned, the uninflected form of a verb is the third-person singular masculine past. We will
            call this the <b>Past Stem</b>. The Past Stem is inflected with suffixes that have the value of a given
            grammatical person (e.g. first-person singular), producing the remaining conjugations of the <b>Past
                Tense</b>. With the exception of some Form 1 verbs that will be discussed later, the Past Stem is always
            voweled with <b>/a/</b>. (We do not take into account epenthetic vowels, which are always <b>/i/</b>.) It
            would be redundant to list the Past Stem of every Verb Form, as the former is equivalent to the uninflected
            pattern of its corresponding Form.</p>
        <p>The <b>Present Stem</b> is derived from the Past Stem & is inflected with both prefixes & suffixes. Observing
            first that the Present Stem always has exactly two vowels (once again not taking into account epenthetic
            vowels), we can say that, as a general rule, the Present Tense is formed by simply inverting the second
            vowel of the Past Stem into <b>/i/</b>, with the following caveats:</p>

        <ul>
            <li>The Present Tense of the passive Forms 5, 5Q & 6, as well as Form 9, are equivalent to their Past
                Stem; there is no vowel inversion. (Note the optionality of the epenthetic vowel in Forms 5, 5Q & 6,
                which begin with a consonant cluster.)
            </li>
            <li>Form X is only distinct from Form 1 in the Past Tense, because their Past Stems have slightly different
                patterns. The Form X Present Stem is formed by treating it as a Form 1 verb; as a result, the two Forms
                are indistinguishable in the tenses & moods formed on the basis of the Present Stem.
            </li>
            <li>In Palestine, all vowels in Forms 4 & 8 — rather than only the second vowel — are inverted to <b>/i/</b>;
                Jordan follows the norm. As a result, for phonotactic reasons (see <b>Phonology: Vowels</b>) the end
                pronunciation of the Present Stem may seem more different from the Past Stem than it would be otherwise.
            </li>
        </ul>

        <p>Here is an illustrative list of Past & Present Stems for each Verb Form, grouped on the basis of similarity
            for ease of comparison:</p>

        <ul>
            <li>Form 1: Past Stem <b>بسط /basaṭ/</b> -> Present Stem <b>بسط /bsiṭ/</b></li>
            <li>Form X: Past Stem <b>أنقذ /ʔanqað/</b> -> Present Stem <b>نقذ /nqið/</b></li>
            <li>Form 4: Past Stem <b>انبسط /inbasaṭ/</b> -> Present Stem <b>انبسط</b> <b>/inbasiṭ/</b> (Jordan) or <b>/inbisiṭ/
                [inbisiṭ, inbsiṭ, inibsiṭ]</b> (Palestine)
            </li>
            <li>Form 8: Past Stem <b>اشتغل /ištaġal/</b> -> Present Stem <b>اشتغل</b> <b>/ištaġil/</b> (Jordan) or <b>/ištiġil/
                [ištġil]</b> (Palestine)
            </li>
            <li>Form 2: Past Stem <b>علّم /ʕallam/</b> -> Present Stem <b>علّم /ʕallim/</b></li>
            <li>Form 5: Past Stem <b>تعلّم /tʕallam/</b> = Present Stem <b>تعلّم /tʕallam/ [tʕallam, itʕallam]</b></li>
            <li>Form 2Q: Past Stem <b>جعلك /žaʕlak/</b> -> Present Stem <b>جعلك /žaʕlik/</b></li>
            <li>Form 5Q: Past Stem <b>تجعلك /tžaʕlak/</b> = Present Stem <b>تجعلك /tžaʕlak/ [tžaʕlak, itžaʕlak]</b></li>
            <li>Form 3: Past Stem <b>ساعد /sāʕad/</b> -> Present Stem <b>ساعد /sāʕid/</b></li>
            <li>Form 6: Past Stem <b>تساعد /tsāʕad/</b> = Present Stem <b>تساعد /tsāʕad/ [tsāʕad, itsāʕad]</b></li>
            <li>Form 7: Past Stem <b>استخدم /istaxdam/</b> -> Present Stem <b>استخدم /istaxdim/</b></li>
            <li>Form 9: Past Stem <b>احمرّ /iħmarr/</b> = Present Stem <b>احمرّ /iħmarr/</b></li>
        </ul>

        <p>The Present Stem is equivalent to the masculine form of the <b>Imperative</b>; the feminine & plural forms
            are created by adding their respective suffixes. One slight exception to this is Form 1 & by extension Form
            X, where the pronunciation of an initial consonant cluster — if there is one — is eased by the addition of a
            phonemic epenthetic vowel that is analogous to that which is observed in the Past Stems of Forms 4, 7, 8 &
            9; hence <b>ابسط (ibsiṭ)</b> & <b>انقذ (inqið)</b>.</p>
        <p>The <b>Subjunctive</b> is formed by attaching a family of prefixes to the Present Stem, discarding any
            epenthetic vowels; the feminine & plural suffixes are used to distinguish those conjugations from their
            masculine counterparts. Finally, the <b>Present Tense</b> is formed by attaching the imperfect prefix <b>بـ
                (b-)</b> to the Subjunctive.</p>
        <p>In order to illustrate the regularity of the inflection of the Present & Past Stems, as well as the
            relationship between the different verbal tenses & moods, here is the same list of verbs conjugated for the
            second-person plural.</p>

        <ul>
            <li>Form 1: Past <b>بسطتو (basaṭtu)</b>; Imperative <b>ابسطو (ibsiṭu)</b>; Subjunctive <b>تبسطو
                (tibsiṭu)</b>; Present <b>بتبسطو (btibsiṭu)</b></li>
            <li>Form X: Past <b>أنقذ (ʔanqaðtu)</b>; Imperative <b>انقذو (inqiðu)</b>; Subjunctive <b>تنقذو
                (tinqiðu)</b>; Present <b>تنقذو (tinqiðu)</b></li>
            <li>Form 4: Past <b>انبسطتو (inbasaṭtu)</b>; Imperative <b>انبسطو (inbisṭu)</b>; Subjunctive <b>تنبسطو
                (tinbisṭu)</b>; Present <b>بتنبسطو (btinbisṭu)</b></li>
            <li>Form 8: Past <b>اشتغلتو (ištaġaltu)</b>; Imperative <b>اشتغلو (ištiġlu)</b>; Subjunctive <b>تشتغلو
                (tištiġlu)</b>; Present <b>بتشتغلو (btištiġlu)</b></li>
            <li>Form 2: Past <b>علّمتو (ʕallamtu)</b>; Imperative <b>علّمو (ʕallmu)</b>; Subjunctive <b>تعلّمو
                (tʕallmu)</b>; Present <b>بتعلّمو (bitʕallmu)</b></li>
            <li>Form 5: Past <b>تعلّمتو (tʕallamtu)</b>; Imperative <b>تعلّمو (tʕallamu)</b>; Subjunctive <b>تتعلّمو
                (titʕallamu)</b>; Present <b>بتتعلّمو (btitʕallamu)</b></li>
            <li>Form 2Q: Past <b>جعلكتو (žaʕlaktu)</b>; Imperative <b>جعلكو (žaʕliku)</b>; Subjunctive <b>تجعلكو
                (tžaʕliku)</b>; Present <b>بتجعلكو (bitžaʕliku)</b></li>
            <li>Form 5Q: Past <b>تجعلكتو (tžaʕlaktu)</b>; Imperative <b>تجعلكو (tžaʕlaku)</b>; Subjunctive <b>تتجعلكو
                (titžaʕlaku)</b>; Present <b>بتتجعلكو (btitžaʕlaku)</b></li>
            <li>Form 3: Past <b>ساعدتو (sāʕadtu)</b>; Imperative <b>ساعدو (sāʕdu)</b>; Subjunctive <b>تساعدو
                (tsāʕdu)</b>; Present <b>بتساعدو (bitsāʕdu)</b></li>
            <li>Form 6: Past <b>تساعدتو (tsāʕadtu)</b>; Imperative <b>تساعدو (tsāʕadu)</b>; Subjunctive <b>تتساعدو
                (titsāʕadu)</b>; Present <b>بتتساعدو (btitsāʕadu)</b></li>
            <li>Form 7: Past <b>استخدمتو (istaxdamtu)</b>; Imperative <b>استخدمو (istaxdimu)</b>; Subjunctive <b>تستخدمو
                (tistaxdimu)</b>; Present <b>بتستخدمو (btistaxdimu)</b></li>
            <li>Form 9: Past <b>احمرّيتو (iħmarrētu)</b>; Imperative <b>احمرّو (iħmarru)</b>; Subjunctive <b>تحمرّو
                (tiħmarru)</b>; Present <b>بتحمرّو (btiħmarru)</b></li>
        </ul>

        <p>Once again, for phonotactic reasons unrelated to morphology voweling may shift slightly in some inflected
            forms (e.g. <b>اشتغل (ištġil)</b> vs. <b>اشتغلو (ištiġlu)</b>); this also accounts for the epenthetic vowel
            sometimes appearing between the imperfect prefix & the personal prefix (i.e. <b>[bit-]</b>) & sometimes
            after both (i.e. <b>[bti-]</b>, as phonemically the compound is simply <b>/bt/</b>.
        </p>
        <p>As seen above, the only inflection that is different from the expected form & thus "irregular" is <b>احمرّيتو
            (iħmarrētu)</b>; this is caused by the final geminated consonant in the Past Stem for reasons that will
            be explained presently.
        </p>

        <p>In addition to the patterning of the verb,
            <Link :href="route('wiki.show', 'roots')">Weak Roots</Link>
            may also have some morphological impact. However, not all Weak Roots have morphological repercussions within
            all Verb
            Forms, or even in all of the inflections of a single given verb. Also, it is largely irrelevant in terms of
            voweling or morphology whether the semivowel is <b>و (w)</b> or <b>ي (y)</b>, except in Form 1D verbs, which
            will be discussed later. Below, then, are the ways in which the Weak Roots affect the Verb Forms:
        </p>
        <ul>
            <li>Type B:</li>
            <ul>
                <li>Forms 1-8: final consonant replaced by <b>ى (-a)</b> in the Past Stem; vowel inversion applies
                    (e.g. <b>سوّى (sawwa)</b>, <b>سوّي (sawwi)</b>)
                </li>
                <li>Form 9: N/A</li>
            </ul>
            <li>Type C:</li>
            <ul>
                <li>Form 1: final two consonants geminated; vowel inversion applies (e.g. <b>حبّ (ħabb)</b>, <b>حبّ
                    (ħibb)</b>)
                </li>
                <li>Form 4: final two consonants geminated; no vowel inversion (e.g. <b>انكبّ
                    (inkabb)</b>, <b>انكبّ (inkabb)</b>)
                </li>
                <li>Form 7: final two consonants geminated; vowel inversion applies (e.g. <b>استغلّ (istaġall)</b>, <b>استغلّ
                    (istaġill)</b>)
                </li>
                <li>Form 8: final two consonants geminated; no vowel inversion (e.g. <b>احتلّ
                    (iħtall)</b>, <b>احتلّ (iħtall)</b>)
                </li>
                <li>Forms 2-3 & 5-6: none</li>
                <li>Form 9: N/A</li>
            </ul>
            <li>Type D:</li>
            <ul>
                <li>Form 1: second consonant replaced by <b>ا (-ā-)</b> in the Past Stem; vowel inversion applies (e.g.
                    <b>ساب (sāb)</b>, <b>سيب (sīb)</b>)
                </li>
                <li>Form 4: second consonant replaced by <b>ا (-ā-)</b> in the Past Stem; no vowel inversion (e.g.
                    <b>انهار (inhār)</b>, <b>انهار (inhār)</b>)
                </li>
                <li>Form 7: second consonant replaced by <b>ا (-ā-)</b> in the Past Stem; no vowel inversion (e.g.
                    <b>استاهل (istāhal)</b>, <b>استاهل (istāhal)</b>)
                </li>
                <li>Form 8: second consonant replaced by <b>ا (-ā-)</b> in the Past Stem; no vowel inversion (e.g.
                    <b>اختار (ixtār)</b>, <b>اختار (ixtār)</b>)
                </li>
                <li>Forms 2-3 & 5-6: none</li>
                <li>Form 9: N/A</li>
            </ul>
            <li>Type E:</li>
            <ul>
                <li>Forms 1-7: none</li>
                <ul>
                    <li>In Form 1, <b>-iw-</b> & <b>-iy-</b> sequences are simplified to <b>-ū-</b> & <b>-ī-</b>,
                        respectively (e.g. <b>بتوصل (btiwṣil > btūṣil)</b>)
                    </li>
                </ul>
                <li>Form 8: semivowel elided & replaced with geminated <b>اتّـ (itt-)</b> (e.g. <b>اتّصل (ittaṣal)</b>,
                    <b>اتّصل (ittṣil)</b>)
                </li>
                <li>Form 9: N/A</li>
            </ul>
        </ul>

        <p>While the inflectional prefixes & suffixes used to conjugate verbs are always the same regardless of Verb
            Form, Weak Verbs (i.e. a verb with a Weak Root) feature a supplementary Past Stem used to form the first- &
            second-person conjugations of the Past Tense. In the case of B-Type & C-Type roots, <b>يـ (ē)</b> links the
            usual Past Stem with the inflectional suffixes. In the case of D-Type roots, the internal long vowel is
            reduced to <b>/a/</b> in Forms 4, 7 & 8 or, in Form 1, to a short vowel with the same quality as that of the
            Present Stem or, if that vowel is <b>/a/</b>, of the semivowel in the root. Because Form 9 always ends in a
            geminated consonant, it always features the same supplementary Past Stem as C-Type Weak Verbs. Note that
            this supplementary Past Stem is never seen on its own, unlike the canonical Past Stem that, left
            uninflected, is equivalent to the third-person masculine past.</p>
        <ul>
            <li>Type A: Past Stem ALL <b>بسط (basaṭ)</b>; Past Tense <b>بسطتو (basaṭtu)</b></li>
            <li>Type B: Past Stem 3P <b>مشى (maša)</b> / 1P-2P <b>مشيـ (mašē-)</b>; Past Tense <b>مشيتو (mašētu)</b>
            </li>
            <li>Type C: Past Stem 3P <b>حبّ (ħabb)</b> / 1P-2P <b>حبّيـ (ħabbē-)</b>; Past Tense <b>حبّيتو (ħabbētu)</b>
            </li>
            <li>Form 9: Past Stem 3P <b>احمرّ (iħmarr)</b> / 1P-2P <b>احمرّيـ (iħmarrē-)</b>; Past Tense <b>احمرّيتو
                (iħmarrētu)</b></li>
            <li>Type D: Past Stem 3P <b>راح (rāħ)</b>, <b>اختار (ixtār)</b> / 1P-2P <b>رح (ruħ-)</b>, <b>اختر
                (ixtar-)</b>; Past Tense <b>رحتو (ruħtu)</b>, <b>اخترتو (ixtartu)</b></li>
        </ul>
        <p>Although all the verbs given above are in Form 1 — with the obvious exception of the Form 9 verb given for
            the comparison — the same principle outlined above applies to all Verb Forms:</p>
        <ul>
            <li>Form 1B: <b>مشى (maša)</b> -> <b>مشيتو (mašētu)</b></li>
            <li>Form 2B: <b>سوّى (sawwa)</b> -> <b>سوّيتو (sawwētu)</b></li>
            <li>Form 3B: <b>نادى (nāda)</b> -> <b>ناديتو (nādētu)</b></li>
        </ul>
        <p>It is for this reason that we say that the patterning of verbs themselves have little to no effect on
            conjugation. Roots have some impact, but always within predictable lines.</p>
        <p>As mentioned before, the default voweling of the Past & Present Stems is <b>/a/</b> & <b>/i/</b>,
            respectively; let us call this <b>regular</b> voweling. Some verbs feature <b>irregular</b> voweling that
            does not follow this pattern. We have already seen that some Form 4, 7 & 8 verbs with certain Weak Roots
            feature no vowel inversion (e.g. <b>اخترتو (ixtartu)</b>, <b>بتختارو (btixtāru)</b>); this is one type of
            irregular voweling that we will call <b>flat</b> voweling, the only type of irregular voweling outside Form
            1. Despite breaking with the default voweling, it occurs here on a predictable basis.</p>
        <p>It is only in Form 1 that vowel patterning is not readily apparent from morphology. Some Form 1 verbs may
            feature flat voweling; others feature <b>reversed</b> voweling, where the voweling of the Past & Present
            Stems is <b>/i/</b> & <b>/a/</b>, respectively, as well as <b>reversed flat</b> voweling, where both Stems
            feature <b>/i/</b>; finally, some feature <b>backed</b> voweling, where the voweling of the Past & Present
            Stems is <b>/a/</b> & <b>/u/</b> (the close back vowel, as opposed to the close front vowel <b>/i/</b>),
            respectively.</p>
        <p>Certain types of voweling only occur among verbs with a certain Root Type; in addition to regular voweling,
            the following vowelings are also possible:</p>
        <ul>
            <li>Type A</li>
            <ul>
                <li>flat (<b>مزح (mazaħ)</b>, <b>امزح (imzaħ)</b>)</li>
                <li>reversed (<b>حضر (ħiḍir)</b>, <b>احضر (iħḍar)</b>)</li>
                <li>reversed flat (<b>مسك (misik)</b>, <b>امسك (imsik)</b>)</li>
                <li>*backed (<b>كتب (katab)</b>, <b>اكتب (uktub)</b>); *the only case where the quality of the
                    epenthetic vowel changes, namely to <b>/u/</b>.
                </li>
            </ul>
            <li>Type B</li>
            <ul>
                <li>*flat (<b>قرا (ʔara)</b>, <b>اقرا (iʔra)</b>); *only due to etymological final hamze</li>
                <li>*reversed (<b>نسي (nisi)</b>, <b>انسى (insa)</b>); *the supplementary Past Tense ends in <b>-ī-</b>
                    rather than <b>-ē-</b></li>
            </ul>
            <li>Type C</li>
            <ul>
                <li>flat (<b>ضلّ (ḍall)</b>, <b>ضلّ (ḍall)</b>)</li>
                <li>backed (<b>ردّ (radd)</b>, <b>ردّ (rudd)</b>)</li>
            </ul>
            <li>Type D</li>
            <ul>
                <li>flat (<b>نام (nām)</b>, <b>نام (nām)</b>)</li>
                <li>backed (<b>راح (rāħ)</b>, <b>روح (rūħ)</b>)</li>
            </ul>
        </ul>
        <p>As may be gleaned from the list above, the voweling of Form 1 verbs cannot be determined by simply looking at
            the verb & is generally arbitrary. Type D verbs are somewhat of an exception in that the quality of the
            vowel in the supplementary Past Stem or the Present Stem is the same as that of the semivowel in the
            root.</p>
        <ul>
            <li>e.g. <b>ر و ح (r-w-ħ)</b> > <b>راح (rāħ)</b>, <b>بتروحو (bitrūħu)</b></li>
        </ul>
        <p>However, they may still feature flat voweling, so that this vowel quality is only a factor in the
            supplementary Past Stem.</p>
        <ul>
            <li>e.g. <b>خ و ف (x-w-f)</b> > <b>خاف (xāf)</b>, <b>بتخافو (bitxāfu)</b>; <b>خفتو (xuftu)</b></li>
        </ul>

        <p>Apparent exceptions to the vowel quality agreement are due to Form X verbs — which
            always feature regular voweling — being reduced to Form 1 verbs.</p>

        <ul>
            <li>e.g. <b>د و ر (d-w-r)</b> > <b>دار (dār)</b>, <b>بتديرو (bitdīru)</b>; etymologically from Form X <b>أدار
                (ʔadār)</b>
            </li>
        </ul>
    </div>
</template>

<!--<x-page-head title="{{ __('affixes') }}"-->
<!--             blurb="Affixes are bound morphemes used to build the inflections of other terms.">-->
<!--    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>-->
<!--    <a>{{ __('morphology') }}</a>-->
<!--    <x-link :href="route('wiki.show', 'affixes')">{{ __('affixes') }}</x-link>-->
<!--</x-page-head>-->

<!--<x-tip>-->
<!--    <p><b>Affixes</b> are not lemmas, nor a grammatical category per se, but they are included in the Dictionary for the-->
<!--        sake of convenience, as they are meaningful morphemes.</p>-->
<!--</x-tip>-->

<!--<x-vue.deck component="DeckContainer" :deck="\App\Models\Deck::find(32)"/>-->
