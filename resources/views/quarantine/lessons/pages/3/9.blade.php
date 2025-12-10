<x-vue.deck component="DeckContainer" :deck="\App\Models\Deck::find(65)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>Prepositionally, <b>معـ (maʕ-)</b> means that something is <b>"with"</b> something else.</p>
    <x-sentence-item eng="there is a car with him">
        <x-sentence-term arb="فيه" eng="there is" :term="$terms['fīh'] ?? null"/>
        <x-sentence-term arb="سيّارة" eng="a car" :term="$terms['sayyāra'] ?? null"/>
        <x-sentence-term arb="معه" eng="with-him" :term="$terms['maʕ'] ?? null"/>
    </x-sentence-item>

    <p>Combining <b>فيه (fīh)</b> & <b>معـ (maʕ-)</b> create <b>"to have"</b> in the sense of having something available
        at hand.</p>
    <x-sentence-item eng="he has a car on hand">
        <x-sentence-term arb="فيه معه" eng="he has" :term="$terms['maʕ'] ?? null"/>
        <x-sentence-term arb="سيّارة" eng="a car" :term="$terms['sayyāra'] ?? null"/>
    </x-sentence-item>

    <p>We can use <b>فيه معه (fīh maʕo)</b> with or without <b>فيه (fīh)</b>. If <b>فيه (fīh)</b> is used, it is negated
        by <b>فش (fiš)</b>. Otherwise, the preposition is negated verbally.</p>
    <x-sentence-item eng="he doesn't have money on hand">
        <x-sentence-term arb="فش معه" eng="3M.has not" :term="$terms['maʕ'] ?? null"/>
        <x-sentence-term arb="مصاري" eng="money" :term="$terms['maṣāri'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="he doesn't have money on hand">
        <x-sentence-term arb="ما معوش" eng="3M.has not" :term="$terms['maʕ'] ?? null"/>
        <x-sentence-term arb="مصاري" eng="money" :term="$terms['maṣāri'] ?? null"/>
    </x-sentence-item>
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

</x-lesson-concept>

<x-lesson-concept section-type="idea" section-title="know this"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">
    <p>We learned in <b>Unit 1</b> that most Arabic terms boil down to root of three or — less commonly — four
        consonants, arranged in a certain pattern. Officially, there are 28 consonants in Arabic — & possibly
        fewer, depending on the dialect of Spoken Arabic — & any combination of these consonants can form a
        valid root; there are no restrictions over the possible arrangements of consonants.</p>
    <p>However, two consonants are not like the others: the two <b>consonant-vowels</b>, <b>و (w)</b> & <b>ي
            (y)</b>. Since they can sound like consonants or vowels, they can change the way a term in a given
        pattern would sound. Consider the most basic noun pattern: <b>CvCC</b>.</p>

    <x-sentence-item eng="lesson">
        <x-sentence-term arb="درس" eng="dars" :term="$terms['dars'] ?? null"/>
    </x-sentence-item>

    <p>As you can see, this term has three clearly identifiable consonants. Now consider the following term:</p>

    <x-sentence-item eng="house">
        <x-sentence-term arb="بيت" eng="bēt" :term="$terms['bēt'] ?? null"/>
    </x-sentence-item>

    <p>At first, it might seem as though this term only has two consonants, with a long vowel in between.
        However, the middle consonant of this term's root is actually <b>ي (y)</b>. It's just that, in the <b>CvCC</b>
        pattern, this results in <b>بيْت (bayt)</b> — as the term is in Standard Arabic — & in Palestinian
        Arabic, the dipthongs <b>ay</b> & <b>aw</b> are flattened to the long vowels <b>ē</b> & <b>ō</b>,
        respectively. Notice, though, that these two terms have the same plural pattern (<b>CCūC</b>), & in this
        pattern <b>ي (y)</b> is realized as a consonant.</p>

    <x-sentence-item eng="lessons">
        <x-sentence-term arb="دروس" eng="drūs" :term="$terms['dars'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="houses">
        <x-sentence-term arb="بيوت" eng="byūt" :term="$terms['bēt'] ?? null"/>
    </x-sentence-item>

    <p>Once we discuss verbs, you will see that consonant vowels can have significant effects on how different
        forms of a verb sound. Arabic verbs may be classified according to a few different metrics, but one of
        them is their <b>root type</b>. As it turns out, triliteral roots may be classified into a handful of
        types, primarily according to whether — & where — they have a <b>consonant vowel</b>. Here are examples
        of each in the <b>CvCC</b> pattern:</p>

    <div class="array">
        <div class="activity-info-container" style="grid-template-columns: repeat(4, 1fr);">
            <div>TYPE</div>
            <div>SEQUENCE</div>
            <div>ROOT</div>
            <div>TERM</div>

            <div>A</div>
            <div>1 2 3</div>
            <div>d r s</div>
            <div>dars</div>

            <div>B</div>
            <div>1 2 V</div>
            <div>m š y</div>
            <div>maši</div>

            <div>C</div>
            <div>1 2 2</div>
            <div>ħ b b</div>
            <div>ħubb</div>

            <div>D</div>
            <div>1 V 3</div>
            <div>b y t</div>
            <div>bēt</div>

            <div>E</div>
            <div>V 2 3</div>
            <div>w ṣ l</div>
            <div>waṣl</div>
        </div>
    </div>
</x-lesson-concept>

{{--<x-lesson-box type="grammar" num="{{$m}}" title="{!! __('lessons.u' . $u . '-l' . $l . '-m' . $m . '-gra') !!}">--}}
{{--    <h1>CONCEPT</h1>--}}
{{--    <p>Prepositionally, <b>فيّـ (fiyy-)</b> means that something is <b>"inside"</b> something else.</p>--}}
{{--    <x-sentence-item eng="there is water in it">--}}
{{--        <x-sentence-term arb="فيه" eng="there is" :term="$terms['fīh'] ?? null" />--}}
{{--        <x-sentence-term arb="ميّ" eng="water" :term="$terms['mayy'] ?? null" />--}}
{{--        <x-sentence-term arb="فيّه" eng="in-it" :term="$terms['bi-'] ?? null" />--}}
{{--    </x-sentence-item>--}}
{{--    <p>Combining <b>فيه (fīh)</b> & <b>فيّـ (fiyy-)</b> creates <b>"to have"</b> in the sense of <b>"to contain"</b>, for something inanimate.</p>--}}
{{--    <x-sentence-item eng="it has water">--}}
{{--        <x-sentence-term arb="فيه فيّه" eng="3M.has" :term="$terms['bi-'] ?? null" />--}}
{{--        <x-sentence-term arb="ميّ" eng="water" :term="$terms['mayy'] ?? null" />--}}
{{--    </x-sentence-item>--}}
{{--    <p>We can use <b>فيه فيّه (fīh fiyyo)</b> with or without <b>فيه (fīh)</b>. However, it is only negated by <b>فش (fiš)</b>.</p>--}}
{{--    <x-sentence-item eng="the bottle doesn't have water">--}}
{{--        <x-sentence-term arb="القنّينة" eng="the-bottle" :term="$terms['ʔannīne'] ?? null" />--}}
{{--        <x-sentence-term arb="فش فيها" eng="3F.has no" :term="$terms['bi-'] ?? null" />--}}
{{--        <x-sentence-term arb="ميّ" eng="water" :term="$terms['mayy'] ?? null" />--}}
{{--    </x-sentence-item>--}}
{{--</x-lesson-box>--}}

{{--<x-lesson-box type="grammar" num="{{$m}}" title="{!! __('lessons.u' . $u . '-l' . $l . '-m' . $m . '-gra') !!}">--}}
{{--    <h1>CONCEPT 1</h1>--}}
{{--    <p>Let's finish our <b>Past Tense</b> conjugation table by learning the remaining endings.</p>--}}
{{--    <p>As for the <b>2nd-Person Past Tense</b>, the <b>2M (أنتا) Past</b> is formed by adding <b>ـت (-t)</b>. Based on--}}
{{--        that, the <b>2F (أنتي) Past</b> is formed by adding <b>ـي (-i)</b> & the <b>2P (أنتو) Past</b> is formed by--}}
{{--        adding <b>ـو (-u)</b>.</p>--}}
{{--    <p>Although we are only learning <b>كان (kān "to be")</b> at the moment, we know some other verbs in the form of--}}
{{--        phrases. We can address people correctly by using the right endings:</p>--}}
{{--    <x-sentence-item eng="please (m.)">--}}
{{--        <x-sentence-term arb="لو" eng="if" :term="$terms['law'] ?? null"/>--}}
{{--        <x-sentence-term arb="سمحت" eng="2M.allowed" :term="$terms['samaħ'] ?? null"/>--}}
{{--    </x-sentence-item>--}}

{{--    <div class="conjugationChart">--}}
{{--        <div>PAST TENSE</div>--}}
{{--        <div>--}}
{{--            <div class="conjugationItem">--}}
{{--                <div>2M</div>--}}
{{--                <div>--}}
{{--                    <div>سمحت</div>--}}
{{--                    <div>samaħt</div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="conjugationItem">--}}
{{--                <div>2F</div>--}}
{{--                <div>--}}
{{--                    <div>سمحتي</div>--}}
{{--                    <div>samaħti</div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div>--}}
{{--            <div class="conjugationItem">--}}
{{--                <div>2P</div>--}}
{{--                <div>--}}
{{--                    <div>سمحتو</div>--}}
{{--                    <div>samaħtu</div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="line" style="height: 0.2rem; margin-top: 2.4rem"></div>--}}
{{--    <h1>CONCEPT 2</h1>--}}
{{--    <p>As for the <b>1st-Person Past Tense</b>, the <b>1S (أنا) Past</b> is formed by adding <b>ـت (-t)</b> — it's--}}
{{--        identical to the <b>2M Past Tense</b>. Meanwhile, the <b>1P (إحنا) Past</b> is formed by adding <b>ـنا (-na)</b>--}}
{{--        — harmonizing with the pronoun.</p>--}}
{{--    <p>Here's the full <b>Past Tense</b> conjugation chart for <b>كان (kān "to be")</b>:</p>--}}

{{--    <p>You will immediately notice that something is unusual in the <b>1st- & 2nd-Person Past Tense</b>: We attached the--}}
{{--        right ending, but the <b>stem</b> of the verb changed too! We will learn why in <b>Unit 4</b>.</p>--}}
{{--</x-lesson-box>--}}

{{--<x-lesson-box type="grammar" num="{{$m}}" title="{{ __('lessons.u' . $u . '-l' . $l . '-m' . $m . '-gra') }}">--}}
{{--    <h1>CONCEPT 1</h1>--}}
{{--    <p>In Arabic, we refer to an unchanging state — whether affirmative or negative — by qualifying it with the word <b>لسّا (lissa "still")</b>:</p>--}}
{{--    <x-sentence-item eng="he is still a student">--}}
{{--        <x-sentence-term arb="هو" eng="he is" :term="$terms['huwwe'] ?? null" />--}}
{{--        <x-sentence-term arb="لسّا" eng="still" :term="$terms['lissa'] ?? null" />--}}
{{--        <x-sentence-term arb="طالب" eng="a student" :term="$terms['ṭālib'] ?? null" />--}}
{{--    </x-sentence-item>--}}
{{--    <x-sentence-item eng="he is not a student yet">--}}
{{--        <x-sentence-term arb="هو" eng="he is" :term="$terms['huwwe'] ?? null" />--}}
{{--        <x-sentence-term arb="لسّا" eng="still" :term="$terms['lissa'] ?? null" />--}}
{{--        <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null" />--}}
{{--        <x-sentence-term arb="طالب" eng="a student" :term="$terms['ṭālib'] ?? null" />--}}
{{--    </x-sentence-item>--}}
{{--    <x-vocabulary title="adverbs">--}}
{{--        <x-term-item :term="$terms['lissa'] ?? null" />--}}
{{--    </x-vocabulary>--}}

{{--    <div class="line" style="height: 0.2rem; margin-top: 2.4rem"></div>--}}
{{--    <h1>CONCEPT 2</h1>--}}
{{--    <p>Additionally, there's a very straightforward & convenient way of saying that a state has changed. When something is <b>"no longer"</b> the case, we use <b>بطّل (baṭṭal)</b>. Since this is a verb, using a pronoun is optional.</p>--}}
{{--    <x-sentence-item eng="he is no longer a student">--}}
{{--        <x-sentence-term arb="بطّل" eng="3M.ceased" :term="$terms['baṭṭal'] ?? null" />--}}
{{--        <x-sentence-term arb="طالب" eng="a student" :term="$terms['ṭālib'] ?? null" />--}}
{{--    </x-sentence-item>--}}
{{--    <x-sentence-item eng="he no longer has money">--}}
{{--        <x-sentence-term arb="بطّل" eng="3M.ceased" :term="$terms['baṭṭal'] ?? null" />--}}
{{--        <x-sentence-term arb="معه" eng="3M.has" :term="$terms['maʕ'] ?? null" />--}}
{{--        <x-sentence-term arb="مصاري" eng="money" :term="$terms['maṣāri'] ?? null" />--}}
{{--    </x-sentence-item>--}}
{{--    <p>Conversely, to say that something wasn't the case, but is <b>"now"</b> the case, we use <b>صار (ṣār)</b>:</p>--}}
{{--    <x-sentence-item eng="he is a student now">--}}
{{--        <x-sentence-term arb="صار" eng="3M.became" :term="$terms['ṣār'] ?? null" />--}}
{{--        <x-sentence-term arb="طالب" eng="a student" :term="$terms['ṭālib'] ?? null" />--}}
{{--    </x-sentence-item>--}}
{{--    <x-sentence-item eng="he has money now">--}}
{{--        <x-sentence-term arb="صار" eng="3M.became" :term="$terms['ṣār'] ?? null" />--}}
{{--        <x-sentence-term arb="معه" eng="3M.has" :term="$terms['maʕ'] ?? null" />--}}
{{--        <x-sentence-term arb="مصاري" eng="money" :term="$terms['maṣāri'] ?? null" />--}}
{{--    </x-sentence-item>--}}
{{--    <x-vocabulary title="verbs">--}}
{{--        <x-term-item :term="$terms['baṭṭal'] ?? null" />--}}
{{--        <x-term-item :term="$terms['ṣār'] ?? null" />--}}
{{--    </x-vocabulary>--}}
{{--</x-lesson-box>--}}
