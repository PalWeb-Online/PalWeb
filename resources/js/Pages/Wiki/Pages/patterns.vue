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
        <h1>Patterns</h1>

        <h2>Contents</h2>
        <ul>
            <li>Introduction</li>
            <li>
                <Link :href="route('wiki.show', 'patterns') + '#singular'">Singular Patterns</Link>
            </li>
            <li>
                <Link :href="route('wiki.show', 'patterns') + '#plural'">Plural Patterns</Link>
            </li>
            <li>
                <Link :href="route('wiki.show', 'patterns') + '#verbal'">Verbal Patterns</Link>
            </li>
            <ul>
                <li>Verb Forms</li>
                <li>Derived Terms</li>
            </ul>
        </ul>

        <h2>Introduction</h2>
        <p>Most terms in Arabic, especially lexical terms like verbs, nouns & adjectives, are structured morphologically
            according to a discernible pattern, a specific sequence of consonants & vowels into which the term's root is
            slotted in. We will refer to these patterns by the structure of that sequence, indicating consonants as
            <b>C</b>, long vowels as <b>L</b> & short vowels as <b>V</b> (e.g. <b>CVCC</b>, <b>CVCCLC</b>, etc.).
            Geminated consonants are indicated with a colon (<b>:</b>), mimicking the IPA lengthening mark.</p>
        <p>For instance, the pattern of <b>شغل (šuġl)</b> is <b>CVCC</b>, while the pattern of <b>شغّال (šaġġāl)</b> is
            <b>CVC:LC</b> or, more specifically, <b>CaC:āC</b>. In cases where there are letters that are invariant in
            a pattern, these letters may be written instead of the wildcards that represent them; hence, <b>CaC:āC</b>
            is a specific configuration of the more general <b>CVC:LC</b> pattern. Not all terms in the same pattern
            will necessarily look the same. <b>Weak Roots</b> (see <b>Morphology: Roots</b>) may affect the surface form
            of the term; for instance, <b>صفّ (ṣaff)</b> is in the <b>CVCC</b> pattern, although the repetition of the
            final two root consonants results in gemination.</p>
        <p>Identifying patterns in Arabic terms is useful for a handful of reasons: As a learning aid, it reduces the
            amount of information that must be recalled for a given term, as the general form of the term is not
            particular or unique to that term itself; rather, the term may be grouped with other terms that sound just
            like it. Additionally, patterns may carry various types of information with them: Some of them have semantic
            value, or are associated with a particular class of word (e.g. elative adjectives), meaning that some of the
            meaning of the word may be inscribed in its very form.</p>
        <p>In terms of syntax, we can group word patterns into three categories: singular patterns, plural patterns &
            verbal patterns. (See <b>Morphology: Verb Forms</b> for more information on verbal patterns & patterns for
            terms derived from verbs, like participles.) Generally speaking, a word will feature a pattern that is in
            accordance with its syntactic properties: verbs are in verbal patterns; plural nouns & adjectives are in
            plural patterns; singular patterns correspond to singular nouns & adjectives, but is also the default for
            other parts of speech that may be in a pattern.</p>
        <p>Arabic terms with the same or similar patterns in their lemma form exhibit similar morphological properties;
            namely, similar singular patterns prefer similar types of plural patterns. Similarity between patterns
            is determined primarily with attention to their length-bearing elements; that is, their consonants & long
            vowels, but not their short vowels. Similarity may be measured according to the following hierarchy:</p>
        <ol>
            <li>the number of length-bearing elements (e.g. <b>CVCC</b> & <b>CLC</b> have three, while <b>CCCC</b> &
                <b>CCLC</b> have four)
            </li>
            <li>the arrangement of length-bearing elements (e.g. <b>CLCC</b> vs. <b>CCLC</b>)</li>
            <li>the values of long vowels (e.g. <b>CCāC</b> vs. <b>CCīC</b>)</li>
            <li>the values of short vowels (e.g. <b>CaCCaC</b> vs. <b>CaCCiC</b>)</li>
        </ol>
        <p>Characteristics further down the list are more specific; for instance, all terms in the <b>CCāC</b> pattern
            are in the <b>CCLC</b> pattern, but not all terms in the <b>CCLC</b> pattern are in the <b>CCāC</b> pattern.
            However, differences in morphological behavior are weaker as the similarity between patterns increases. We
            can be as general or as specific as we wish when describing a pattern. Hence, the word <b>كتاب (ktāb)</b>
            may be described as <b>CCāC</b> (more specific) or <b>CCLC</b> (less specific).</p>
        <p>On PalWeb, patterns are listed separately from other patterns similar to it when they have distinguishing
            grammatical features (e.g. a certain semantic association, a plural pattern reserved for that pattern,
            etc.). Since <b>ة (-e)</b> may appear in any pattern with no morphological repercussions, the patterns
            listed below should be understood to imply its possibility; <b>زلمة (zalame)</b>, then, is in the
            <b>CVCVC</b> pattern. Only <b>CVCCe</b> is listed separately from <b>CVCC</b>, because there it has a
            reserved plural pattern.</p>
        <p>Patterns like these are a feature of all Arabic varieties & more broadly of Semitic languages, where raw
            consonantal strings (i.e. roots) carry semantic value. Such patterning makes the voweling of terms highly
            predictable in Arabic, underpinning the viability & practicality of using an abjad — which doesn't represent
            short vowels — as a writing system for Arabic. All productive patterns in Spoken Arabic can accommodate
            either three or four consonants specifically; there are no patterns based on, say, two or five consonants,
            but rather terms of unusual length are usually loanwords or functional terms like prepositions &
            conjunctions with an ad hoc structure that cannot be said to belong to a specific pattern. Since roots are
            inferred from terms by extracting them from the surrounding pattern, we can assert that roots in Spoken
            Arabic can only ever be triliteral or quadriliteral.</p>
        <p>Patterns are not simply highly productive; new coinages — even loanwords — must respect them as much as
            possible. When a term is loaned into Arabic, where possible it is fit into one of the language's patterns.
            Sometimes this process is incomplete precisely due to the unavailability of patterns that can accommodate
            the required number of consonants; this was common in loans from Persian, the source of many supposedly
            five-consonant roots. If we examine things more closely, however, we find that Arabic discards consonants
            freely in order to fit the term into its pattern logic. Hence <b>برنامج (barnāmaž, "program")</b> is
            pluralized as <b>برامج (barāmiž)</b> — implying the root <b>ب ر م ج (b-r-m-ž)</b> — while the verb is the
            Form 2Q <b>برمج (barmaž, "to program")</b>, whence <b>برمجة (barmaže, "programming")</b>.</p>
        <p>Spoken Arabic largely shares the same patterns as Standard Arabic, although the inventory of productive
            patterns has been streamlined. In some cases, the voweling of the pattern is simplified in Spoken Arabic as
            a result of the same historical phonological processes that are observed more generally in the language (see
            <b>Phonology: Vowels</b>). This is especially true of initial open syllables, which are reduced & merged
            into the following syllable to form a consonant cluster (e.g. <b>CVCLC</b> > <b>CCLC</b>). Because this
            phonological change is native to the dialect, the pattern's canonical form observes it, even though borrowed
            terms in the pattern may feature the classical voweling; in fact, this non-native voweling is one factor
            that allows us to identify borrowed terms as such. By this criterion, then, <b>جمال (žamāl)</b> is
            considered a borrowed term in the <b>CCāC</b> pattern.</p>
        <ul>
            <li>Intensives & Defects have specific syntactic uses & so always have the same feminine & plural forms,
                whereas the other two can have any plural form.
            </li>
        </ul>

        <h2 id="singular">Singular Patterns</h2>
        <p>We will refer to the following as singular patterns, because they fundamentally supply the singular forms of
            nouns & adjectives. Terms belonging to other parts of speech, especially adverbs, may appear to be in one of
            these patterns, but they are generally nouns or adjectives that have been adverbialized.</p>
        <ul>
            <li>Length 3</li>
            <ul>
                <li><b>CLC</b></li>
                <ul>
                    <li><b>CLC</b> — only among Type D Weak Roots</li>
                    <ul>
                        <li><b>باب (bāb, "door")</b></li>
                        <li><b>سوق (sūʔ, "market")</b></li>
                        <li><b>عيد (ʕīd, "holiday")</b></li>
                    </ul>
                </ul>
                <li><b>CCC</b></li>
                <ul>
                    <li><b>CVCC</b></li>
                    <ul>
                        <li><b>شمس (šams, "sun")</b></li>
                        <li><b>بيت (bēt, "house")</b></li>
                        <li><b>صفّ (ṣaff, "class")</b></li>
                    </ul>
                    <li><b>CVCCe</b> — equivalent to <b>CVCC</b>, but has a common plural pattern reserved for it</li>
                    <ul>
                        <li><b>غرفة (ġurfe, "room")</b></li>
                        <li><b>دولة (dōle, "country")</b></li>
                        <li><b>شقّة (šaʔʔa, "apartment")</b></li>
                    </ul>
                    <li><b>CVCVC</b></li>
                    <ul>
                        <li><b>بلد (balad, "town")</b></li>
                        <li><b>قمر (ʔamar, "moon")</b></li>
                        <li><b>زلمة (zalame, "man")</b></li>
                    </ul>
                    <li><b>CiCiC</b> — limited to adjectives</li>
                    <ul>
                        <li><b>نغش (niġiš, "cute")</b></li>
                        <li><b>سهل (sihil, "easy")</b></li>
                        <li><b>حلو (ħilu, "sweet")</b></li>
                    </ul>
                </ul>
            </ul>
            <li>Length 4</li>
            <ul>
                <li><b>CLCC</b></li>
                <ul>
                    <li><b>CāCiC</b> (a.k.a. <b>Form 1 AP</b> pattern)</li>
                    <ul>
                        <li><b>شاطر (šāṭir, "smart")</b></li>
                        <li><b>ثانية (ŧānye, "(time) second")</b></li>
                        <li><b>خايف (xāyif, "scared")</b></li>
                    </ul>
                </ul>
                <li><b>CCLC</b></li>
                <ul>
                    <li><b>CCLC</b> (<b>CCūC</b> by default)</li>
                    <ul>
                        <li><b>زبون (zbūn, "customer")</b></li>
                        <li><b>خروف (xarūf, "lamb")</b></li>
                        <li><b>سخونة (sxūne, "fever")</b></li>
                    </ul>
                    <li><b>CCāC</b></li>
                    <ul>
                        <li><b>كتاب (ktāb, "book")</b></li>
                        <li><b>جمال (žamāl, "beauty")</b></li>
                        <li><b>بخار (buxār, "steam")</b></li>
                    </ul>
                    <li><b>CCīC</b></li>
                    <ul>
                        <li><b>رغيف (rġīf, "loaf (of bread)")</b></li>
                        <li><b>صديق (ṣadīq, "friend")</b></li>
                        <li><b>مدينة (madīne, "city")</b></li>
                    </ul>
                </ul>
                <li><b>CCCC</b></li>
                <ul>
                    <li><b>CVCCVC</b></li>
                    <ul>
                        <li><b>زعتر (zaʕtar, "thyme")</b></li>
                        <li><b>تكسي (taksy, "taxi")</b></li>
                        <li><b>أغنية (ʔuġniye, "song")</b></li>
                    </ul>
                    <li><b>maCCaC</b> or <b>maCCaCe</b>, less often <b>maCCiC</b> — common among nouns referring to a
                        place
                    </li>
                    <ul>
                        <li><b>مكتب (maktab, "office")</b></li>
                        <li><b>مدرسة (madrase, "school")</b></li>
                        <li><b>مسجد (masžid, "mosque")</b></li>
                    </ul>
                    <li><b>ʔaCCaC</b> — Defect Adjective pattern, including Elative Adjectives (see <b>Synax:
                        Adjectives</b>)
                    </li>
                    <ul>
                        <li><b>أحسن (ʔaħsan, "better")</b></li>
                        <li><b>أحمر (ʔaħmar, "red")</b></li>
                        <li><b>أصلع (ʔaṣlaʕ, "bald")</b></li>
                    </ul>
                </ul>
            </ul>
            <li>Length 5</li>
            <ul>
                <li><b>CCCLC</b></li>
                <ul>
                    <li><b>CVCCLC</b></li>
                    <ul>
                        <li><b>ختيار (xityār, "elderly person")</b></li>
                        <li><b>أسبوع (ʔusbūʕ, "week")</b></li>
                        <li><b>قنّينة (ʔannīne, "bottle")</b></li>
                    </ul>
                    <li><b>CaCCān</b> — Intensive Adjective pattern (see <b>Synax: Adjectives</b>)</li>
                    <ul>
                        <li><b>زعلان (zaʕlān, "upset")</b></li>
                        <li><b>سهران (sahrān, "staying up all night")</b></li>
                        <li><b>جوعان (žūʕān, "hungry")</b></li>
                    </ul>
                </ul>
                <li><b>CC:LC</b></li>
                <ul>
                    <li><b>CaC:āC</b> — common among nouns referring to the doer of an action</li>
                    <ul>
                        <li><b>طبّاخ (ṭabbāx, "chef")</b></li>
                        <li><b>غسّالة (ġassāle, "washing machine")</b></li>
                        <li><b>خفّاش (xaffāš, "bat (animal)")</b></li>
                    </ul>
                    <li><b>CaC:īC</b></li>
                    <ul>
                        <li><b>أكّيل (ʔakkīl, "gluttonous")</b></li>
                        <li><b>بطّيخ (baṭṭīx, "watermelon")</b></li>
                        <li><b>بكّير (bakkīr, "early")</b></li>
                    </ul>
                </ul>
            </ul>
        </ul>

        <h2 id="plural">Plural Patterns</h2>
        <p>Additional word patterns exist that are strongly or exclusively associated with the plural forms of nouns or
            adjectives; these are known as <b>broken plurals</b>, which contrast with <b>sound plurals</b> in that they
            are not based on suffixation.</p>
        <p>It is often said that the plural form of a noun or adjective cannot be determined or inferred on the basis of
            its singular form. But this is not entirely true, as morphological, syntactic & even semantic factors all
            play a role in limiting the ways a term could be pluralized. Most notably, adjectives always have sound
            plurals, except for those in the <b>CCīC</b> pattern, which may have a broken plural in the <b>CCāC</b>
            pattern, & Defect Adjectives, which always have a broken plural in the <b>CuCuC</b> pattern. Because of the
            patterned nature of broken plurals, moreover, singular nouns that don't fall into any discernible pattern
            generally have no plural pattern either; they have sound plurals. Other tendencies may be observed: human
            nouns in the <b>CaC:āC</b> pattern usually have sound plurals ending in <b>ين (-īn)</b>, & nouns ending in
            <b>ة (-e)</b> are more likely to have a sound plural ending in <b>ات (-āt)</b> than ones that don't.</p>
        <p>Even if a term has a broken plural, the precise selection of the plural pattern is not entirely random. While
            not all terms in the same singular pattern necessarily feature the same plural pattern, the plural pattern
            is usually selected from a pool of patterns specific to patterns of the same length, with sometimes multiple
            plural forms being possible. If we group plural patterns by the length of the singular patterns they are
            associated with, we will find that there is practically no overlap between these pools. Exceptions include
            the <b>CCāC</b> plural pattern, which is available to all Length 3 terms (e.g. <b>باب (bāb)</b> > <b>بواب
                (bwāb)</b>), but also to Length 4 adjectives in the <b>CCīC</b> pattern (e.g. <b>كبير (kbīr)</b> > <b>كبار
                (kbār)</b>), as well as the <b>CVCCān</b> pattern, which is available to either Length 3 or 4 terms
            indiscriminately.</p>
        <p>Aside from rare, sporadic exceptions (e.g. <b>أرض (ʔarḍ)</b> > <b>أراضي (ʔarādy)</b>), only one systematic
            exception exists wherein terms of a given length draw from a different plural pattern pool: certain relative
            adjectives that refer to humans — mostly, but not exclusively, demonyms — may optionally be forced to fit in
            the <b>CaCāCCe</b> pattern regardless of the morphology of the lemma form, even with the addition of extra
            consonants if needed (e.g. <b>مصريّ (maṣri)</b> > <b>مصاروة (maṣārwe)</b>).</p>
        <ul>
            <li>Length 3</li>
            <ul>
                <li><b>CCūC</b></li>
                <ul>
                    <li><b>أسد (ʔasad, "lion")</b> > <b>أسود (ʔusūd, "lions")</b></li>
                    <li><b>بنك (bank, "bank")</b> > <b>بنوك (bnūk, "banks")</b></li>
                </ul>
                <li><b>ʔaCCāC</b></li>
                <ul>
                    <li><b>برج (burž, "tower")</b> > <b>أبراج (ʔabrāž, "towers")</b></li>
                    <li><b>عضو (ʕuḍu, "member")</b> > <b>أعضاء (ʔaʕḍāʔ, "members")</b></li>
                </ul>
                <li><b>CVCaC</b> — limited to & very common among terms in the <b>CVCC</b> pattern ending in <b>ة
                    (-e)</b></li>
                <ul>
                    <li><b>علبة (ʕilbe, "box")</b> > <b>علب (ʕilab, "boxes")</b></li>
                    <li><b>شقّة (šaʔʔa, "apartment")</b> > <b>شقق (šuʔaʔ, "apartments")</b></li>
                </ul>
            </ul>
            <li>Length 3 / 4</li>
            <ul>
                <li><b>CCāC</b> (Length 3)</li>
                <ul>
                    <li><b>باب (bāb, "door")</b> > <b>بواب (bwāb, "doors")</b></li>
                    <li><b>زلمة (zalame, "man")</b> > <b>زلام (zlām, "men")</b></li>
                </ul>
                <li><b>CCāC</b> (Length 4) — limited to adjectives in the <b>CCīC</b> pattern</li>
                <ul>
                    <li><b>كبير (kbīr, "(s.) big")</b> > <b>كبار (kbār, "(p.) big")</b></li>
                    <li><b>رخيص (rxīṣ, "(s.) cheap")</b> > <b>رخاص (rxāṣ, "(p.) cheap")</b></li>
                </ul>
                <li><b>CVCCān</b></li>
                <ul>
                    <li><b>ثور (tōr, "bull")</b> > <b>ثيران (tīrān, "bulls")</b></li>
                    <li><b>قميص (ʔamīṣ, "shirt")</b> > <b>قمصان (ʔumṣān, "shirts")</b></li>
                </ul>
            </ul>
            <li>Length 4</li>
            <ul>
                <li><b>CaCāCiC</b></li>
                <ul>
                    <li><b>مكتب (maktab, "office")</b> > <b>مكاتب (makātib, "offices")</b></li>
                    <li><b>شارع (šāriʕ, "street")</b> > <b>شوارع (šawāriʕ, "streets")</b></li>
                </ul>
                <li><b>CuC:āC</b> — limited to nouns in the <b>CāCiC</b> pattern that refer to humans</li>
                <ul>
                    <li><b>كاتب (kātib, "writer")</b> > <b>كتّاب (kuttāb, "writers")</b></li>
                    <li><b>عامل (ʕāmil, "worker")</b> > <b>عمّال (ʕummāl, "workers")</b></li>
                </ul>
                <li><b>CuCuC</b> — limited to terms in a <b>CCLC</b> pattern</li>
                <ul>
                    <li><b>كتاب (ktāb, "book")</b> > <b>كتب (kutub, "books")</b></li>
                    <li><b>مبينة (madīne, "city")</b> > <b>مدن (mudun, "cities")</b></li>
                </ul>
                <li><b>ʔaCCiCe</b> — near-limited to terms in the <b>CCāC</b> pattern</li>
                <ul>
                    <li><b>سؤال (suʔāl, "question")</b> > <b>أسئلة (ʔasʔile, "questions")</b></li>
                    <li><b>دوا (dawa, "medicine")</b> > <b>أدوية (ʔadwiye, "medicines")</b></li>
                </ul>
                <li><b>CCīC</b> — limited to terms in the <b>CCāC</b> pattern</li>
                <ul>
                    <li><b>حمار (ħmār, "donkey")</b> > <b>حمير (ħmīr, "donkeys")</b></li>
                </ul>
                <li><b>CuCaCa</b> — limited to terms in the <b>CCīC</b> pattern that refer to humans</li>
                <ul>
                    <li><b>فقير (faqīr, "(s.) poor")</b> > <b>فقرا (fuqara, "(p.) poor")</b></li>
                    <li><b>رئيس (raʔīs, "president")</b> > <b>رؤسا (ruʔasa, "presidents")</b></li>
                </ul>
            </ul>
            <li>Length 5</li>
            <ul>
                <li><b>CaCāCīC</b></li>
                <ul>
                    <li><b>مكنتوب (maktūb, "letter")</b> > <b>مكاتيب (makātīb, "letters")</b></li>
                    <li><b>شبّاك (šubbāk, "window")</b> > <b>شبابيك (šabābīk, "windows")</b></li>
                </ul>
                <li><b>CaCāCCe</b> — limited to terms that refer to humans; available to certain relative adjectives
                </li>
                <ul>
                    <li><b>أستاذ (ʔustāz, "professor")</b> > <b>أساتذة (ʔasātze, "professors")</b></li>
                    <li><b>مصريّ (maṣri, "Egyptian")</b> > <b>مصاروة (maṣārwe, "Egyptians")</b></li>
                </ul>
            </ul>
        </ul>

        <h2 id="verbal">Verbal Patterns</h2>
        <h3>Verb Forms</h3>
        <p>In Arabic, the uninflected form of a verb (i.e. the dictionary or lemma form) is the third-person singular
            masculine past. Verbs may be grouped according to the patterning of this form of the verb. In Arabic, these
            patterns are known as <b>أوزان (ʔawzān)</b> & each is referred to by the pattern itself using the
            placeholder root <b>فعل (f-ʕ-l)</b>; for instance, a common pattern for causative verbs is <b>فعّل
                (faʕʕal)</b>. In English, these patterns are usually referred to as <b>Verb Forms</b> & they are known
            by an arbitrary numbering that has become widespread; for instance, the <b>فعّل (faʕʕal)</b> pattern is
            known in English as <b>Form 2</b>. Standard Arabic pedagogy usually refers to ten primary Verb Forms for
            triliteral roots & two for quadriliteral roots, although additional rarer patterns exist for both types of
            roots.</p>
        <ul>
            <li>Standard Arabic</li>
            <ul>
                <li>Form 1: <b>فعل (faʕala, faʕila, faʕula)</b></li>
                <li>Form 2: <b>فعّل (faʕʕala)</b></li>
                <li>Form 3: <b>فاعل (fāʕala)</b></li>
                <li>Form 4: <b>أفعل (ʔafʕala)</b></li>
                <li>Form 5: <b>تفعّل (tafaʕʕala)</b></li>
                <li>Form 6: <b>تفاعل (tafāʕala)</b></li>
                <li>Form 7: <b>انفعل (anfaʕala)</b></li>
                <li>Form 8: <b>افتعل (aftaʕala)</b></li>
                <li>Form 9: <b>افعلّ (afʕalla)</b></li>
                <li>Form 10: <b>استفعل (astafʕala)</b></li>
                <li>Form Q1: <b>فعلق (faʕlaqa)</b></li>
                <li>Form Q2: <b>تفعلق (tfaʕlaqa)</b></li>
            </ul>
        </ul>
        <p>In Palestinian Arabic, there are nine native patterns for triliteral roots, with an additional non-native
            pattern attested in learned borrowings from Standard Arabic: all of the above patterns have been inherited
            in Palestinian Arabic, except for Form 4, which has merged into Form 1; the voweling patterns for Form 1
            have also been simplified. Here, then, are the Palestinian Arabic analogues of the Standard Arabic verbal
            patterns, in the order they would traditionally be listed.</p>
        <ul>
            <li>Spoken Arabic (Palestinian)</li>
            <ul>
                <li>Form 1: <b>فعل (faʕal, fiʕil)</b></li>
                <li>Form 2: <b>فعّل (faʕʕal)</b></li>
                <li>Form 3: <b>فاعل (fāʕal)</b></li>
                <li>Form 4: <b>أفعل (ʔafʕal)</b> [non-native]</li>
                <li>Form 5: <b>تفعّل (tfaʕʕal)</b></li>
                <li>Form 6: <b>تفاعل (tfāʕal)</b></li>
                <li>Form 7: <b>انفعل (infaʕal)</b></li>
                <li>Form 8: <b>افتعل (iftaʕal)</b></li>
                <li>Form 9: <b>افعلّ (ifʕall)</b></li>
                <li>Form 10: <b>استفعل (istafʕal)</b></li>
                <li>Form Q1: <b>فعلق (faʕlaq)</b></li>
                <li>Form Q2: <b>تفعلق (tfaʕlaq)</b></li>
            </ul>
        </ul>
        <p>The Verb Forms carry some semantic value that interacts with the semantic value of the root. There is also a
            pattern of active-passive semantic relationships between the Forms themselves, by which the passive
            counterparts of Forms 2, 3 & Q1 are Forms 5, 6 & Q2. (Here, "passive" refers broadly to unaccusative,
            reflexive & reciprocal senses.) In Standard Arabic, Form 7 very often & Form 8 sometimes is the passive
            counterpart of a Form 1 verb & sometimes a Form 4 verb. In Palestinian Arabic, however, Form 8 is rarely
            passive & Form 4 has merged into Form 1. As a result, the active-passive semantic relationship between Form
            1 & 7 has become strengthened & highly regularized. In the spirit of describing Spoken Arabic in a way that
            is most internally coherent, then, on PalWeb I've adjusted the ordering of the Forms as follows:</p>
        <ul>
            <li>Spoken Arabic (Palestinian)</li>
            <ul>
                <li>Form 1: <b>فعل (faʕal, fiʕil)</b></li>
                <li>Form 2: <b>فعّل (faʕʕal)</b></li>
                <li>Form 2Q: <b>فعلق (faʕlaq)</b></li>
                <li>Form 3: <b>فاعل (fāʕal)</b></li>
                <li>Form 4: <b>انفعل (infaʕal)</b> ["Form 7"]</li>
                <li>Form 5: <b>تفعّل (tfaʕʕal)</b></li>
                <li>Form 5Q: <b>تفعلق (tfaʕlaq)</b></li>
                <li>Form 6: <b>تفاعل (tfāʕal)</b></li>
                <li>Form 7: <b>استفعل (istafʕal)</b> ["Form 10"]</li>
                <li>Form 8: <b>افتعل (iftaʕal)</b></li>
                <li>Form 9: <b>افعلّ (ifʕall)</b></li>
                <li>Form X: <b>أفعل (ʔafʕal)</b> ["Form 4"]</li>
            </ul>
        </ul>
        <p>The purpose of this reordering is: 1) to reinforce the semantic relationships between Forms 1-2-3 & 4-5-6; 2)
            to indicate that Form X ("Form 4") is non-native & usually has an native Form 1 counterpart; 3) in the case
            of Form 7 ("Form 10"), to avoid affecting Forms 8 & 9 by a cascading effect. I've also chosen to list the
            two quadriliteral patterns alongside their triliteral analogues.</p>
        <p>Finally, one small note about phonology & orthography: In Standard Arabic, Forms "7", 8, 9 & "10" begin with
            a phonemic epenthetic vowel, which is maintained in their Spoken Arabic counterparts & usually written
            accordingly (e.g. <b>اشتغل (ištaġal)</b>). In Palestinian Arabic, some speakers may pronounce Forms 5 & 6
            with an optional epenthetic vowel, depending on their tolerance of initial consonant clusters; they may
            reflect this in writing as well (e.g. <b>تحمّم (tħammam)</b> vs. <b>اتحمّم (itħammam)</b>). Since this vowel
            is not phonemic & not all speakers insert it, I have chosen to maintain the Standard Arabic orthography as
            the canonical orthography for these terms on PalWeb. (See <b>Phonology: Vowels</b> for more information.)
        </p>
        <p>See <b>Syntax: Verbs</b> for a detailed discussion of the semantic values of the Verb Forms & their
            relationships.</p>

        <!--    <p><b>Forms 4-9</b> all begin with consonant clusters, meaning epenthetic insertion before onset is possible for-->
        <!--        all-->
        <!--        of them. <b>Forms 5 & 6</b> are thus sometimes represented orthographically with an <b>epenthetic vowel</b>-->
        <!--        (e.g. <b>اتحمّم</b>). My decision to retain the prescribed spellings — without removing the <b>epenthetic-->
        <!--            vowel</b> altogether — is to reflect mainstream practices & to avoid confusion, as not all speakers-->
        <!--        avoid-->
        <!--        initial consonant clusters.</p>-->

        <h3>Derived Terms</h3>
        <p>There are three types of terms that are directly derived from verbs: the <b>active participle (AP)</b>, the
            <b>passive participle (PP)</b> & the <b>verbal noun (VN)</b>; their usage is described in further detail in
            <b>Syntax: Verbs</b>. As for their formation, it is highly regular, with the exception of Form 1 VNs. All
            APs & PPs aside from Form 1 are formed by adding <b>مـ (m-)</b> to the verb's Present Stem & Past Stem,
            respectively; in Standard Arabic this prefix is <b>مـ (mu-)</b>, so it appears this way in borrowed terms.
            Form 1 has specific AP & PP patterns, listed below.
        </p>
        <ul>
            <li>Form 1: <b>فعل (faʕal)</b></li>
            <ul>
                <li>AP: <b>فاعل (fāʕil)</b>; e.g. <b>حابب (ħābib)</b>, <b>رايح (rāyiħ)</b></li>
                <li>PP:<b>مفعول (mafʕūl)</b>; e.g. <b>مضغوط (maḍġūṭ)</b>, <b>مبسوط (mabsūṭ)</b></li>
            </ul>
            <li>Form 2: <b>فعّل (faʕʕal)</b>; all other forms follow this pattern</li>
            <ul>
                <li>AP: <b>مفعّل (mfaʕʕil)</b></li>
                <li>PP: <b>مفعّل (mfaʕʕal)</b></li>
            </ul>
        </ul>
        <p>All forms aside from Form 1 have a default VN pattern. In addition to the default pattern, Forms 2 & 3 have
            uncommon alternative patterns that are sometimes seen. Form 1 has no default pattern for the VN; there
            are a few common ones, but ultimately the VN may appear in practically any triconsonantal pattern. It should
            be noted that the vast majority of VNs display the phonological characteristics of borrowed terms. Note that
            Weak Roots can interact with some of these patterns to produce slightly different results.</p>
        <ul>
            <li>Form 1</li>
            <ul>
                <li><b>فعل (faʕl)</b></li>
                <li><b>فعالة (fiʕāle)</b></li>
                <li><b>فعول (fuʕūl)</b></li>
            </ul>
            <li>Form 2</li>
            <ul>
                <li><b>تفعيل (tafʕīl)</b>; e.g. <b>ترتيب (tartīb)</b></li>
                <li>(uncommon) <b>تفعلة (tafʕule)</b>; e.g. <b>تجربة (tažrube)</b></li>
            </ul>
            <li>Form 3</li>
            <ul>
                <li><b>مفاعلة (mufāʕale)</b>; e.g. <b>مقابلة (muqābale)</b></li>
                <li>(uncommon) <b>فعال (fiʕāl)</b>; e.g. <b>وفاق (wifāq)</b></li>
            </ul>
            <li>Form 4: <b>انفعال (infiʕāl)</b>; e.g. <b>انفجار (infižār)</b></li>
            <li>Form 5: <b>تفعّل (tafaʕʕul)</b>; e.g. <b>تخرّج (taxarruž)</b></li>
            <li>Form 6: <b>تفاعل (tafāʕul)</b>; e.g. <b>تعامل (taʕāmul)</b></li>
            <li>Form 7: <b>استفعال (istifʕāl)</b>; e.g. <b>استخدام (istixdām)</b></li>
            <li>Form 8: <b>افتعال (iftiʕāl)</b>; e.g. <b>احتفال (iħtifāl)</b></li>
        </ul>
        <!--        <ChartConjugation :patterns="[{-->
        <!--            'type': 'verbal',-->
        <!--            'form': '1',-->
        <!--            'pattern': 'A1u',-->
        <!--            'pattern_alias': 'A1u'-->
        <!--        }]"-->
        <!--                          :roots="[{-->
        <!--            'ar': ['ك', 'ت', 'ب'],-->
        <!--            'en': ['k', 't', 'b'],-->
        <!--            'dialect': 'test'-->
        <!--        }]"-->
        <!--                          :derivatives="[]"-->
        <!--        />-->

        <!--        <p>Note that, because the semantic values of roots & patterns are abstract, the exact meaning of a given verb-->
        <!--            cannot be derived mechanically from the addition of the root & the pattern.</p>-->

        <h2>Search Filters</h2>
        <p>On PalWeb, terms are tagged by their pattern, making it possible to filter your search by word pattern & see
            only terms in a given pattern. Within the search filters on the Dictionary index page or in the Search
            Genie, there are three menus associated with patterns: the Form filter, Singular filter & Plural filter.</p>

        <ul>
            <li><b>maCCaC</b> & <b>CaC:āC</b> are listed in the dropdown, but not Defect; Intensives are listed in a
                different section
            </li>
            <li>explain what the purpose of the singular/plural dropdowns is</li>
            <li>mention that these patterns are found in the Singular dropdown, which also includes relative adjs &
                nominalized adjs for the sake of convenience (see Affixes)
            </li>
        </ul>
    </div>
</template>

