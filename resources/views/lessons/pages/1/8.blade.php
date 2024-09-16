<x-deck-container :deck="\App\Models\Deck::find(45)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>In addition to agreeing with the <b>gender & number</b> of nouns, adjectives in Arabic must agree with the noun
        in <b>definiteness</b>. If a noun is definite, any adjective that modifies it must definite as well — indicated
        by the article <b>الـ (-l)</b>. Naturally, not doing so results in an ordinary nominal sentence.</p>
    <div class="array">
        <x-sentence-item eng="a big building">
            <x-sentence-term arb="عمارة" eng="building" :term="$terms['ʕimāra'] ?? null"/>
            <x-sentence-term arb="كبيرة" eng="big" :term="$terms['kbīr'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="the big building">
            <x-sentence-term arb="العمارة" eng="the-building" :term="$terms['ʕimāra'] ?? null"/>
            <x-sentence-term arb="الكبيرة" eng="the-big" :term="$terms['kbīr'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="the building is big">
            <x-sentence-term arb="العمارة" eng="the-building" :term="$terms['ʕimāra'] ?? null"/>
            <x-sentence-term arb="كبيرة" eng="big" :term="$terms['kbīr'] ?? null"/>
        </x-sentence-item>
    </div>
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

    <p>Now that we know all forms of grammatical agreement for adjectives, we can describe things more specifically.
        Let's learn to ask others to specify things as well, using the word <b>أيّ (ʔayy "which")</b>:</p>
    <x-sentence-item eng="which neighborhood do you live in?">
        <x-sentence-term arb="إنتا" eng="you.M" :term="$terms['ʔinta'] ?? null"/>
        <x-sentence-term arb="ساكن" eng="living" :term="$terms['sākin'] ?? null"/>
        <x-sentence-term arb="بـ" eng="in" :term="$terms['b--preposition'] ?? null"/>
        <x-sentence-term arb="ـأيّ" eng="which" :term="$terms['ʔayy'] ?? null"/>
        <x-sentence-term arb="حيّ؟" eng="neighborhood" :term="$terms['ħayy'] ?? null"/>
    </x-sentence-item>
    <p>In addition to <b>أيّ (ʔayy)</b>, Palestinian Arabic provides interrogative versions of the three <b>demonstrative
            determiners</b> — <b>هادا (hāda)</b>, <b>هادي (hādi)</b> & <b>هدول (hadōl)</b>. They sound like mergers of
        <b>أيّ
            (ʔayy)</b> with the subject pronouns <b>هو (huwwe)</b>, <b>هي (hiyye)</b> & <b>همّه (humme)</b>.</p>

    <x-inflections
        conjM="أنوه" conjMtr="which?"
        conjF="أنيه" conjFtr="which?"
        conjP="أنمّه" conjPtr="which?"
    ></x-inflections>

    <p>In principle, they work the same & fulfill the same function as <b>أيّ (ʔayy)</b>:</p>
    <x-sentence-item eng="which neighborhood do you live in?">
        <x-sentence-term arb="إنتا" eng="you.M" :term="$terms['ʔinta'] ?? null"/>
        <x-sentence-term arb="ساكن" eng="living" :term="$terms['sākin'] ?? null"/>
        <x-sentence-term arb="بـ" eng="in" :term="$terms['b--preposition'] ?? null"/>
        <x-sentence-term arb="ـأنوه" eng="which" :term="$terms['ʔanūh'] ?? null"/>
        <x-sentence-term arb="حيّ؟" eng="neighborhood" :term="$terms['ħayy'] ?? null"/>
    </x-sentence-item>

    <p>So why use them? Because <b>أيّ (ʔayy)</b> is exclusively a <b>determiner</b>, meaning it must have a referent;
        it
        cannot be used by itself. In contrast, <b>أنوه (ʔanūh)</b>, <b>أنيه (ʔanīh)</b> & <b>أنمّه (ʔanumme)</b> — like
        their <b>demonstrative</b> counterparts — can stand by themselves as pronouns, meaning <b>"which
            one(s)?"</b>.</p>
    <div class="array">
        <x-sentence-item eng="which one?">
            <x-sentence-term arb="أنيه؟" eng="which.F" :term="$terms['ʔanīh'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="I live in a village">
            <x-sentence-term arb="أنا" eng="I" :term="$terms['ʔana'] ?? null"/>
            <x-sentence-term arb="ساكن" eng="living" :term="$terms['sākin'] ?? null"/>
            <x-sentence-term arb="بـ" eng="in" :term="$terms['b--preposition'] ?? null"/>
            <x-sentence-term arb="ـقرية" eng="village" :term="$terms['qarye'] ?? null"/>
        </x-sentence-item>
    </div>
    <x-sentence-item eng="which one is good?">
        <x-sentence-term arb="أنوه" eng="which.M" :term="$terms['ʔanūh'] ?? null"/>
        <x-sentence-term arb="منيح" eng="good.M" :term="$terms['mnīħ'] ?? null"/>
    </x-sentence-item>
    <p>Once you've memorized them, you'll probably find them easier & more natural to use than <b>أيّ
            (ʔayy)</b>, as they don't require you to specify or repeat the referent every single time.</p>
</x-lesson-concept>

<x-activity-area title="{{ __('exercise') }}">
    <p>All the students are living in Birzeit while they're studying, but they're living in different places on the
        same street. Use the following information about the street they live on & everyone's place of residence to
        complete the following
        exercises.</p>

    <div class="array">
        <div class="activity-info-container" style="grid-template-columns: repeat(4, 1fr);">
            <div>ميرا</div>
            <div>يارا</div>
            <div>جريس</div>
            <div>شادي</div>
            <div style="grid-column: span 4">شارع</div>
            <div>سما</div>
            <div>دانا</div>
            <div>غسّان</div>
            <div>أكرم</div>
        </div>
    </div>

    <div class="array">
        <div class="activity-info-container" style="grid-template-columns: repeat(3, 1fr);">
            <div>طالب</div>
            <div style="grid-column: span 2">مكان</div>

            <div>أكرم</div>
            <div>بيت</div>
            <div>قديم</div>

            <div>غسّان</div>
            <div>عمارة</div>
            <div>زغيرة</div>

            <div>شادي</div>
            <div>بيت</div>
            <div>جديد</div>

            <div>جريس</div>
            <div>عمارة</div>
            <div>كبيرة</div>

            <div>يارا</div>
            <div>عمارة</div>
            <div>قديمة</div>

            <div>دانا</div>
            <div>بيت</div>
            <div>كبير</div>

            <div>سما</div>
            <div>عمارة</div>
            <div>جديدة</div>

            <div>ميرا</div>
            <div>بيت</div>
            <div>زغير</div>
        </div>
    </div>

    <p>{{ __('activity.multiple-choice') }}</p>
    <div class="question-group shuffle">
        <x-activity-mc que="أنوه طالب ساكن ببيت؟" ans="d"
                       a="دانا"
                       b="غسّان"
                       c="جريس"
                       d="أكرم"
        />
        <x-activity-mc que="أنوه طالب ساكن بعمارة؟" ans="c"
                       a="سما"
                       b="شادي"
                       c="جريس"
                       d="أكرم"
        />
        <x-activity-mc que="أنيه طالبة ساكنة ببيت؟" ans="b"
                       a="يارا"
                       b="ميرا"
                       c="سما"
                       d="شادي"
        />
        <x-activity-mc que="أنيه طالبة ساكنة بعمارة؟" ans="a"
                       a="يارا"
                       b="ميرا"
                       c="دانا"
                       d="غسّان"
        />
    </div>

    <p>{{ __('activity.complete-sentence') }}</p>
    <div class="activity-dialog">
        <x-dialog-line speaker="سؤال" arb="مين ساكن قدّام شادي؟" eng="who lives in front of Shadi?"/>
        <x-dialog-line speaker="جواب" arb="أكرم ساكن قدّام شادي" eng="Jeries lives in front of Shadi"/>
    </div>
    <div class="question-group shuffle">
        <x-activity-fill
            que="مين ساكن جنب أكرم؟"
            ans="غسّان ساكن جنب أكرم"/>
        <x-activity-fill
            que="مين ساكنة جنب ميرا؟"
            ans="يارا ساكنة جنب ميرا"/>
        <x-activity-fill
            que="مين ساكنين جنب غسّان؟"
            ans="أكرم ودانا ساكنين جنب غسّان"/>
        <x-activity-fill
            que="مين ساكنين جنب يارا؟"
            ans="جريس وميرا ساكنين جنب يارا"/>

        <x-activity-fill
            que="مين ساكن قدّام جريس؟"
            ans="غسّان ساكن قدّام جريس"/>
        <x-activity-fill
            que="مين ساكنة قدّام دانا؟"
            ans="يارا ساكنة قدّام دانا"/>
        <x-activity-fill
            que="مين ساكن بين شادي ويارا؟"
            ans="جريس ساكن بين شادي ويارا"/>
        <x-activity-fill
            que="مين ساكنة بين غسّان وسما؟"
            ans="دانا ساكنة بين غسّان وسما"/>
    </div>

    <p>{{ __('activity.complete-sentence') }}</p>
    <div class="activity-dialog">
        <x-dialog-line speaker="سؤال" arb="جريس ساكن بأنيه عمارة؟" eng="in which building does Jeries live?"/>
        <x-dialog-line speaker="جواب" arb="جريس ساكن بالعمارة الكبيرة" eng="Jeries lives in the big building"/>
    </div>
    <div class="question-group shuffle">
        <x-activity-fill
            que="أكرم ساكن بأنوه بيت؟"
            ans="أكرم ساكن بالبيت القديم"/>
        <x-activity-fill
            que="غسّان ساكن بأنيه عمارة؟"
            ans="غسّان ساكن بالعمارة الزغيرة"/>
        <x-activity-fill
            que="شادي ساكن بأنوه بيت؟"
            ans="شادي ساكن بالبيت الجديد"/>
        <x-activity-fill
            que="يارا ساكنة بأنيه عمارة؟"
            ans="يارا ساكنة بالعمارة القديمة"/>
        <x-activity-fill
            que="دانا ساكنة بأنوه بيت؟"
            ans="دانا ساكنة بالبيت الكبير"/>
        <x-activity-fill
            que="سما ساكنة بأنيه عمارة؟"
            ans="سما ساكنة بالعمارة الجديدة"/>
    </div>
</x-activity-area>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">

    <p>Discussing our shared experiences in Palestinian Arabic is easy using <b>كمان (kamān "also")</b>.
        While it may be used as a mere transition word, we can use it with a noun in the sense of <b>"too"</b> as well.
    </p>

    <div class="array">
        <x-sentence-item eng="me too">
            <x-sentence-term arb="و" eng="&" :term="$terms['w-'] ?? null"/>
            <x-sentence-term arb="أنا" eng="I" :term="$terms['ʔana'] ?? null"/>
            <x-sentence-term arb="كمان" eng="also" :term="$terms['kamān'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="I am from Palestine">
            <x-sentence-term arb="أنا" eng="I" :term="$terms['ʔana'] ?? null"/>
            <x-sentence-term arb="من" eng="from" :term="$terms['min'] ?? null"/>
            <x-sentence-term arb="فلسطين" eng="Palestine" :term="$terms['falasṭīn'] ?? null"/>
        </x-sentence-item>
    </div>
    <x-sentence-item eng="Shadi is also from Palestine">
        <x-sentence-term arb="شادي" eng="Shadi"/>
        <x-sentence-term arb="كمان" eng="also" :term="$terms['kamān'] ?? null"/>
        <x-sentence-term arb="من" eng="from" :term="$terms['min'] ?? null"/>
        <x-sentence-term arb="فلسطين" eng="Palestine" :term="$terms['falasṭīn'] ?? null"/>
    </x-sentence-item>

    <p>Notice that we're constantly learning new interjections & transition terms, like <b>يلّا (yalla "alright")</b>,
        <b>بصراحة (b-ṣarāħa "honestly")</b> & <b>ع فكرة (ʕa fikra "actually")</b>. We can use these to guide the flow of
        the conversation & say exactly what we mean. Use them to sound way more natural!</p>
</x-lesson-concept>

<x-activity-area title="{{ __('dialogue') }}">
    <p>Shadow the following conversation. Ghassan introduces Jeries to Rafat, a diaspora Palestinian visiting
        Birzeit whom Ghassan had just met.</p>
    <div class="activity-dialog">
        <x-dialog-line speaker="غسّان" arb="جريس، هادا رأفت — رأفت، جريس"/>
        <x-dialog-line speaker="جريس" arb="تشرّفنا يا رأفت، كيف الحال؟"/>
        <x-dialog-line speaker="رأفت" arb="الحمدلله — إلي الشرف"/>
        <x-dialog-line speaker="غسّان" arb="رأفت ساكن بأميركا"/>
        <x-dialog-line speaker="جريس" arb="بسّ إنتا من فلسطين، صح؟"/>
        <x-dialog-line speaker="رأفت" arb="آه، طبعًا — أنا من قرية قريبة من هون"/>
        <x-dialog-line speaker="جريس" arb="عن جدّ؟ أنيه قرية؟"/>
        <x-dialog-line speaker="رأفت" arb="سلواد"/>
        <x-dialog-line speaker="جريس" arb="والله، أهلا وسهلا"/>
        <x-dialog-line speaker="غسّان" arb="ع فكرة، أنا كمان من القرى"/>
        <x-dialog-line speaker="رأفت" arb="مش إنتا من نابلس؟"/>
        <x-dialog-line speaker="غسّان" arb="يعني، من قرية جنب نابلس"/>
        <x-dialog-line speaker="جريس" arb="إنتا عارف وين بيتا؟"/>
        <x-dialog-line speaker="رأفت" arb="أكيد، إنتو ساكنين ببيتا؟"/>
        <x-dialog-line speaker="غسّان" arb="طبعًا لأ — إحنا ساكنين ببيرزيت"/>
        <x-dialog-line speaker="جريس" arb="بسّ هو من بيتا — أنا لأ، أنا من القدس"/>
        <x-dialog-line speaker="رأفت" arb="والله، أنيه منطقة؟"/>
        <x-dialog-line speaker="جريس" arb="أبو ديس"/>
        <x-dialog-line speaker="رأفت" arb="أهلا وسهلا"/>
    </div>
    <x-activity-mc que="وين ساكن رأفت؟" ans="b"
                   a="فلسطين"
                   b="أميركا"
                   c="بيرزيت"
                   d="سلواد"
    />
    <x-activity-mc que="رأفت من أنيه قرية؟" ans="d"
                   a="فلسطين"
                   b="أميركا"
                   c="بيرزيت"
                   d="سلواد"
    />
    <x-activity-mc que="مين من قرية قريبة من نابلس؟" ans="a"
                   a="غسّان"
                   b="جربس"
                   c="رأفت"
    />
    <x-activity-mc que="مين من قرية قريبة من بيرزيت؟" ans="c"
                   a="غسّان"
                   b="جربس"
                   c="رأفت"
    />
</x-activity-area>
