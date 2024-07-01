<x-deck :deck="\App\Models\Deck::find(46)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p><b>Active Participles</b>, as a general rule, denote states rather than progressive actions. Only one exceptional
        category exists: a handful of movement verbs that are used literally like <b>Present Participles</b> in English.
    </p>

    <x-sentence eng="I am going to the store">
        <x-sentence-term arb="أنا" eng="I" :term="$terms['ʔana'] ?? null"/>
        <x-sentence-term arb="رايح" eng="going" :term="$terms['rāyiħ'] ?? null"/>
        <x-sentence-term arb="عـ" eng="to" :term="$terms['ʕala'] ?? null"/>
        <x-sentence-term arb="ـالدكّان" eng="the-store" :term="$terms['dukkān'] ?? null"/>
    </x-sentence>
    <x-sentence eng="we are leaving work">
        <x-sentence-term arb="إحنا" eng="we" :term="$terms['ʔiħna'] ?? null"/>
        <x-sentence-term arb="طالعين" eng="leaving" :term="$terms['ṭāliʕ'] ?? null"/>
        <x-sentence-term arb="من" eng="from" :term="$terms['min'] ?? null"/>
        <x-sentence-term arb="الشغل" eng="the-work" :term="$terms['šuḡl'] ?? null"/>
    </x-sentence>
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

    <p>We've already learned many prepositions, including two of the most important: <b>بـ (b- "in")</b> & <b>على (ʕala
            "on")</b>. As it turns out, both of these terms actually have multiple meanings. Let's learn about another
        way to use them now.</p>
    <p>First, <b>على (ʕala)</b> has the meaning of <b>"to, toward"</b> as well:</p>
    <x-sentence eng="he is going to the hospital">
        <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
        <x-sentence-term arb="رايح" eng="going" :term="$terms['rāyiħ'] ?? null"/>
        <x-sentence-term arb="عـ" eng="to" :term="$terms['ʕala'] ?? null"/>
        <x-sentence-term arb="ـالمستشفى" eng="the-hospital" :term="$terms['mustašfa'] ?? null"/>
    </x-sentence>
    <p>Second, <b>بـ (b-)</b> has the meaning of <b>"by, using"</b> as well:</p>
    <x-sentence eng="he is coming by bus">
        <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
        <x-sentence-term arb="جاي" eng="coming" :term="$terms['jāy'] ?? null"/>
        <x-sentence-term arb="بـ" eng="by" :term="$terms['b-'] ?? null"/>
        <x-sentence-term arb="ـالباص" eng="the-bus" :term="$terms['bāṣ'] ?? null"/>
    </x-sentence>

    <p>It's important to note that <b>بـ (b-)</b> in the sense of <b>"in"</b> may never be used to indicate
        the direction of movement (i.e. <b>"into"</b>); <b>على (ʕala)</b> must be used for all direction of movement.
    </p>
</x-lesson-concept>

<x-activity-area title="{{ __('exercise') }}">
    <p>Indicate whether you think the travel described seems possible or not:</p>
    <div class="question-group" shuffle>
        <x-activity-mc que="أنا رايح عالشغل من البيت بالبسكليت" ans="a"
                       a="ممكن"
                       b="مش ممكن"
        />
        <x-activity-mc que="أنا جاي عالأردن من بريطانيا بالباص" ans="b"
                       a="ممكن"
                       b="مش ممكن"
        />
        <x-activity-mc que="أنا طالع من المحطّة بالبسكليت" ans="a"
                       a="ممكن"
                       b="مش ممكن"
        />
        <x-activity-mc que="أنا رايح من الفندق عالمطار بالطيّارة" ans="b"
                       a="ممكن"
                       b="مش ممكن"
        />
        <x-activity-mc que="أنا رايح عالمستشفى بالقطار" ans="a"
                       a="ممكن"
                       b="مش ممكن"
        />
        <x-activity-mc que="أنا طالع من المكتب بالطيّارة" ans="b"
                       a="ممكن"
                       b="مش ممكن"
        />
        <x-activity-mc que="أنا راجع ع حيفا من القدس بالباص" ans="a"
                       a="ممكن"
                       b="مش ممكن"
        />
        <x-activity-mc que="أنا جاي ع فلسطين من أميركا بالقطار" ans="b"
                       a="ممكن"
                       b="مش ممكن"
        />
    </div>
</x-activity-area>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">

    <p>By now, we've learned a variety of terms to add some color & naturalism to our conversations. Now, let's learn
        how to express different degrees of certainty. We already know how to literally say that we don't know
        something, but did you know there's a single word — <b>أبصر (ʔabṣar "don't know")</b> —
        that expresses the same thing? It expresses a stronger sense of cluelessness.</p>
    <x-sentence eng="(I) don't know where he is">
        <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null"/>
        <x-sentence-term arb="عارف" eng="aware" :term="$terms['ʕārif'] ?? null"/>
        <x-sentence-term arb="وين" eng="where" :term="$terms['wēn'] ?? null"/>
        <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
    </x-sentence>
    <x-sentence eng="(I) don't know where he is">
        <x-sentence-term arb="أبصر" eng="don't know" :term="$terms['ʔabṣar'] ?? null"/>
        <x-sentence-term arb="وين" eng="where" :term="$terms['wēn'] ?? null"/>
        <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
    </x-sentence>
    <p>Alternatively, we can use terms like <b>يمكن (yimkin "maybe")</b> & <b>بجوز (bižūz "maybe")</b> when we're not
        certain about the information we're providing:</p>
    <x-sentence eng="maybe he's at the office">
        <x-sentence-term arb="يمكن" eng="maybe" :term="$terms['yimkin'] ?? null"/>
        <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
        <x-sentence-term arb="بـ" eng="in" :term="$terms['b--preposition'] ?? null"/>
        <x-sentence-term arb="ـالمكتب" eng="the-office" :term="$terms['maktab'] ?? null"/>
    </x-sentence>
    <p>Similarly, we can softly agree with something using the non-commital <b>أظن (ʔaẓonn "I guess")</b> — or we can
        use it like the previous terms, with a slightly more certain connotation:</p>
    <x-sentence eng="I guess he's at the office">
        <x-sentence-term arb="أظنّ" eng="I guess" :term="$terms['ʔaẓonn'] ?? null"/>
        <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
        <x-sentence-term arb="بـ" eng="in" :term="$terms['b--preposition'] ?? null"/>
        <x-sentence-term arb="ـالمكتب" eng="the-office" :term="$terms['maktab'] ?? null"/>
    </x-sentence>
</x-lesson-concept>
