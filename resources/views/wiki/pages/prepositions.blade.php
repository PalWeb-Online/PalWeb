<x-page-head title="{{ __('prepositions') }}"
             blurb="Prepositions are non-nouns that are always the head of a genitive structure.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'syntax')">{{ __('syntax') }}</x-link>
    <x-link :href="route('wiki.show', 'prepositions')">{{ __('prepositions') }}</x-link>
</x-page-head>

<p><b>Prepositions</b> are known in Arabic as <b>حروف جرّ</b> (i.e. genitive particles). As their
    name in Arabic suggests, they instantiate the <b>genitive case</b>. Because of this, pronouns that follow
    prepositions are in their <b>clitic form</b>. Many prepositions have distinct <b>host forms</b> to hold clitics:
</p>

<div class="array">
    <x-sentence-item eng="in it">
        <x-sentence-term arb="فيـ" eng="in" :term="$terms->firstWhere('translit', 'b-')"/>
        <x-sentence-term arb="ـها" eng="F.it" :term="$terms->firstWhere('translit', '-ha')"/>
    </x-sentence-item>
    <x-sentence-item eng="in the car">
        <x-sentence-term arb="بـ" eng="in" :term="$terms->firstWhere('translit', 'b-')"/>
        <x-sentence-term arb="ـالسيّارة" eng="the-car" :term="$terms->firstWhere('translit', 'sayyāra')"/>
    </x-sentence-item>
</div>

<div class="array">
    <x-sentence-item eng="for him">
        <x-sentence-term arb="إلـ" eng="for" :term="$terms->firstWhere('translit', 'la-')"/>
        <x-sentence-term arb="ـه" eng="him" :term="$terms->firstWhere('translit', '-o')"/>
    </x-sentence-item>
    <x-sentence-item eng="for the boy">
        <x-sentence-term arb="لـ" eng="for" :term="$terms->firstWhere('translit', 'la-')"/>
        <x-sentence-term arb="ـلولد" eng="the-boy" :term="$terms->firstWhere('translit', 'walad')"/>
    </x-sentence-item>
</div>

<p><b>Prepositions</b> are a semi-closed category; different regions may have alternatives for these terms. <b>Prepositions</b>
    include the following terms & their variants:</p>

<x-vue.deck component="DeckContainer" :deck="\App\Models\Deck::find(35)"/>

<div class="wiki-container">
    <h1>Prepositions & Verbs</h1>
    <p>Generally speaking, Arabic is very literal in its use of prepositions. Sometimes, though, certain prepositions
        are used — namely with verbs — in a way that doesn't translate very well into English. These usages may be
        divided into three categories:</p>
    <ol>
        <li><b>Verbal Prepositions</b> are prepositions used as verbs.</li>
        <li><b>Prepositional Verbs</b> are combinations of verbs & prepositions that have idiomatic meanings.</li>
        <li><b>Prepositional Patient Markers</b> are prepositions used to indicate the semantic patient of a verb.</li>
    </ol>

    <h2>Verbal Prepositions</h2>
    <p>See <a href="{{ route('wiki.show', 'verbs') }}">the copula</a> & <a href="{{ route('wiki.show', 'verbs') }}">pseudo-verbs</a>.
        Some prepositions are used as verbs with idiomatic meanings; they are a subset of <b>prepositional
            verbs</b> formed specifically by attaching prepositions to the <b>null copula</b>.</p>
    <x-sentence-item eng="I have a friend in London">
        <x-sentence-term arb="فيه إلي" eng="1S.have" :term="$terms->firstWhere('translit', 'la-')"/>
        <x-sentence-term arb="صديق" eng="friend" :term="$terms->firstWhere('translit', 'ṣadīq')"/>
        <x-sentence-term arb="بـ" eng="in" :term="$terms->firstWhere('translit', 'b-')"/>
        <x-sentence-term arb="ـلندن" eng="London"/>
    </x-sentence-item>

    <h2>Prepositional Verbs</h2>
    <p>Some verbs convey distinct idiomatic meanings when paired with certain prepositions. Since these multi-term verbs
        cannot be broken down into individual parts, a preposition in this case cannot be analyzed as a unit with its
        own independent meaning.</p>

    <x-vocabulary>
        <x-term-item arb="ردّ (على)" eng="to reply (to)"/>
        <x-term-item arb="ردّ على" eng="to obey, to do as (someone) says"/>
    </x-vocabulary>

    <x-sentence-item eng="he replied to the message">
        <x-sentence-term arb="ردّ" eng="3M.replied" :term="$terms->firstWhere('translit', 'radd')"/>
        <x-sentence-term arb="على" eng="to" :term="$terms->firstWhere('translit', 'ʕala')"/>
        <x-sentence-term arb="الرسالة" eng="the-message" :term="$terms->firstWhere('translit', 'risāle')"/>
    </x-sentence-item>
    <x-sentence-item eng="he obeyed his parents">
        <x-sentence-term arb="ردّ على" eng="3M.obeyed" :term="$terms->firstWhere('translit', 'radd ʕala')"/>
        <x-sentence-term arb="أهله" eng="his-parents" :term="$terms->firstWhere('translit', 'ʔahl')"/>
    </x-sentence-item>

    <h2>Patient Markers</h2>
    <p>A handful of prepositions are used to indicate the semantic direct or indirect patient of an action. Each one
        conveys a different relationship of the action & its patient; i.e. whether someone benefitted from it, suffered
        from it, etc. Sometimes these prepositions build new idiomatic meanings.</p>

    <h3>على</h3>

    <p>Indicates the sufferer of an action over which the sufferer has no control or influence.</p>

    <x-sentence-item eng="he missed out on it">
        <x-sentence-term arb="راح" eng="3M.went" :term="$terms->firstWhere('translit', 'rāħ')"/>
        <x-sentence-term arb="عليه" eng="(against-him)" :term="$terms->firstWhere('translit', 'ʕala')"/>
    </x-sentence-item>
    <x-sentence-item eng="he backstabbed him">
        <x-sentence-term arb="حكى" eng="3M.spoke" :term="$terms->firstWhere('translit', 'ħaka')"/>
        <x-sentence-term arb="عليه" eng="(against-him)" :term="$terms->firstWhere('translit', 'ʕala')"/>
    </x-sentence-item>

    <p>It is sometimes an exact
        antonym of <b>لـ (la-)</b>, which indicates the beneficiary of an action, although both may translate as
        <b>"for"</b> in English.</p>

    <x-sentence-item eng="clear a way for the ambulance">
        <x-sentence-term arb="إفتح" eng="2M.open" :term="$terms->firstWhere('translit', 'fataħ')"/>
        <x-sentence-term arb="طريق" eng="road" :term="$terms->firstWhere('translit', 'ṭarīʔ')"/>
        <x-sentence-term arb="لـ" eng="for" :term="$terms->firstWhere('translit', 'la-')"/>
        <x-sentence-term arb="سيّارة الإسعاف" eng="the-ambulance" :term="$terms->firstWhere('translit', 'ʔisʕāf')"/>
    </x-sentence-item>

    <x-sentence-item eng="don't cut off the ambulance">
        <x-sentence-term arb="ما" eng="don't" :term="$terms->firstWhere('translit', 'mā')"/>
        <x-sentence-term arb="تسكّر" eng="2M.close" :term="$terms->firstWhere('translit', 'sakkar')"/>
        <x-sentence-term arb="الطريق" eng="the-road" :term="$terms->firstWhere('translit', 'ṭarīʔ')"/>
        <x-sentence-term arb="عليه" eng="(against)" :term="$terms->firstWhere('translit', 'ʕala')"/>
        <x-sentence-term arb="سيّارة الإسعاف" eng="the-ambulance" :term="$terms->firstWhere('translit', 'ʔisʕāf')"/>
    </x-sentence-item>

    <h3>لـ</h3>

    <p>Indicates the recipient or beneficiary of an action. This is usually understood as an indirect object marker, but
        it should be noted that it may be used with practically any verb.</p>

    <h3>مع</h3>

    indicates the recipient of an action; it refers to the person or thing that is affected by the occurrence of an
    action that is deemed to have happened automatically or uncontrollably (usually an intransitive verb).
    هاذا اللي طلع معك؟

    In contrast, the referent of مع (maʕ “with”) carries out the action alongside the subject.

    <h3>بـ</h3>

    <p>In addition to being an instrumental preposition, <b>بـ (b-)</b> indicates the semantic direct object of an
        intransitive verb. While its use as an instrumental preposition indicates what is used to carry out the action,
        this sense indicates the target or location of the action.</p>
    <p>It focuses on the doer of the action over the object, giving the sense that the action is not instantaneous, but
        rather is a continuous process for which the semantic object was used.</p>

    <x-vocabulary>
        <x-term-item arb="صوّر" eng="to take a picture"/>
    </x-vocabulary>
    <x-sentence-item eng="he took a picture of her">
        <x-sentence-term arb="صوّرها" eng="3M.photographed-her" :term="$terms->firstWhere('translit', 'fakkar')"/>
    </x-sentence-item>
    <x-sentence-item eng="he took pictures of her">
        <x-sentence-term arb="صوّر" eng="3M.photographed" :term="$terms->firstWhere('translit', 'fakkar')"/>
        <x-sentence-term arb="فيها" eng="her" :term="$terms->firstWhere('translit', 'b-')"/>
    </x-sentence-item>

    <x-vocabulary>
        <x-term-item arb="فكّر" eng="(intransitive) to think"/>
        <x-term-item arb="فكّر" eng="(ditransitive) to consider (someone a certain way)"/>
    </x-vocabulary>
    <x-sentence-item eng="he thought about him">
        <x-sentence-term arb="فكّر" eng="3M.thought" :term="$terms->firstWhere('translit', 'fakkar')"/>
        <x-sentence-term arb="فيه" eng="him" :term="$terms->firstWhere('translit', 'b-')"/>
    </x-sentence-item>
    <x-sentence-item eng="he considered him good">
        <x-sentence-term arb="فكّره" eng="3M.thought-him" :term="$terms->firstWhere('translit', 'fakkar')"/>
        <x-sentence-term arb="منيح" eng="to" :term="$terms->firstWhere('translit', 'mnīħ')"/>
    </x-sentence-item>

    <x-vocabulary>
        <x-term-item arb="مسك" eng="(intransitive) to hold on"/>
        <x-term-item arb="مسك" eng="(transitive) to grab"/>
    </x-vocabulary>
    <x-sentence-item eng="hold onto it">
        <x-sentence-term arb="إمسك" eng="2M.hold on" :term="$terms->firstWhere('translit', 'masak')"/>
        <x-sentence-term arb="فيه" eng="it" :term="$terms->firstWhere('translit', 'b-')"/>
    </x-sentence-item>
    <x-sentence-item eng="grab it">
        <x-sentence-term arb="إمسكه" eng="2M.grab-it" :term="$terms->firstWhere('translit', 'masak')"/>
    </x-sentence-item>

    1. (instrumental preposition; marks the thing used to carry out an action) by, using, by means of.
    أنا بروح عالشغل كل يوم بالبسكليت
    مش ضروريّ تسكّر الباب بالقفل

    1. (mark ) with
    صارلك ساعة وإنتا بتلعب بشواربك

</div>
