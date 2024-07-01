<x-deck :deck="\App\Models\Deck::find(59)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>We have used <b>فيه (fīh)</b> with <b>عند (ʕind)</b> in a way that already implies possession:</p>
    <x-sentence eng="there is food at my place">
        <x-sentence-term arb="فيه" eng="there is" :term="$terms['fīh'] ?? null"/>
        <x-sentence-term arb="أكل" eng="food" :term="$terms['ʔakl'] ?? null"/>
        <x-sentence-term arb="عندي" eng="at-me" :term="$terms['ʕind'] ?? null"/>
    </x-sentence>

    <p>By pairing <b>فيه (fīh)</b> & <b>عند (ʕind)</b> directly together, we create the verb <b>"to have"</b> in Arabic:
    </p>
    <x-sentence eng="I have food">
        <x-sentence-term arb="فيه عندي" eng="I have" :term="$terms['ʕind'] ?? null"/>
        <x-sentence-term arb="أكل" eng="food" :term="$terms['ʔakl'] ?? null"/>
    </x-sentence>

    <p>We negate this by negating <b>فيه (fīh)</b>:</p>
    <x-sentence eng="I don't have food">
        <x-sentence-term arb="فش عندي" eng="I don't have" :term="$terms['ʕind'] ?? null"/>
        <x-sentence-term arb="أكل" eng="food" :term="$terms['ʔakl'] ?? null"/>
    </x-sentence>
    <p>While <b>فيه (fīh)</b> & <b>عند (ʕind)</b> create the most general sense of <b>"to have"</b>, we may pair <b>فيه
            (fīh)</b> with three other prepositions as well, with similar effects; each of these will be studied
        individually later.</p>

    <p>In fact, <b>فيه (fīh)</b> in this construction is optional & we can use the prepositions verbally by themselves:
    </p>
    <x-sentence eng="I have food">
        <x-sentence-term arb="عندي" eng="at-me" :term="$terms['ʕind'] ?? null"/>
        <x-sentence-term arb="أكل" eng="food" :term="$terms['ʔakl'] ?? null"/>
    </x-sentence>

</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

    <p>Some body parts that occur in pairs are pluralized by <b>ين (-ēn)</b>; although similar to the dual, they are
        simply plural. Most importantly, these forms lose the final <b>ـن (-n)</b> when a clitic pronoun is added:</p>
    <x-sentence eng="hands">
        <x-sentence-term arb="إيدين" eng="hands" :term="$terms['ʔīd'] ?? null"/>
    </x-sentence>
    <x-sentence eng="your hands">
        <x-sentence-term arb="إيديـ" eng="hands" :term="$terms['ʔīd'] ?? null"/>
        <x-sentence-term arb="ـك" eng="your" :term="$terms['-ak'] ?? null"/>
    </x-sentence>
</x-lesson-concept>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">

    <p>We can describe our feelings using the Active Participle <b>حاسس (ħāsis "feeling")</b>, followed either by an
        adjective or by a noun with <b>بـ (b-)</b>:</p>

    <x-sentence eng="I feel sick">
        <x-sentence-term arb="أنا" eng="I" :term="$terms['ʔana'] ?? null"/>
        <x-sentence-term arb="حاسس" eng="feeling" :term="$terms['ħāsis'] ?? null"/>
        <x-sentence-term arb="مريض" eng="sick" :term="$terms['mrīḍ'] ?? null"/>
    </x-sentence>

    <x-sentence eng="I feel pain in my back">
        <x-sentence-term arb="أنا" eng="I" :term="$terms['ʔana'] ?? null"/>
        <x-sentence-term arb="حاسس" eng="feeling" :term="$terms['ħāsis'] ?? null"/>
        <x-sentence-term arb="بـ" eng="in" :term="$terms['b-'] ?? null"/>
        <x-sentence-term arb="وجع" eng="pain" :term="$terms['wažaʕ'] ?? null"/>
        <x-sentence-term arb="بالضهر" eng="in-the-back" :term="$terms['ḍahar'] ?? null"/>
    </x-sentence>
</x-lesson-concept>

<x-activity-area title="{{ __('exercise') }}">
    <p>Complete the sentences using the most appropriate phrase.</p>

    <div class="question-group">
        <x-activity-mc que="أنا حاسس" ans="a"
                       a="مريض"
                       b="مشغول"
                       c="منخاري"
        />
        <x-activity-mc que="أنا حاسس بـ" ans="c"
                       a="شوبان"
                       b="البطن"
                       c="وجع بالظهر"
        />
        <x-activity-mc que="عندي" ans="a"
                       a="مشكلة بالإجرين"
                       b="بردان"
                       c="مريض"
        />
        <x-activity-mc que="أنا حاسس" ans="b"
                       a="وجع راس"
                       b="تعبان"
                       c="مشكلة بالدنين"
        />
        <x-activity-mc que="أنا حاسس بـ" ans="c"
                       a="تعبان"
                       b="مريض"
                       c="وجع بالظهر"
        />
        <x-activity-mc que="عندي" ans="a"
                       a="وجع راس"
                       b="بردان"
                       c="البطن"
        />
    </div>
</x-activity-area>

<x-activity-area title="{{ __('dialogue') }}">
    <p>Shadow the following conversation between ? & ? in ?:</p>
    <div class="activity-dialog">
        <x-dialog-line speaker="مريض" arb="مرحبا دكتور، يعطيك العافية"/>
        <x-dialog-line speaker="دكتور" arb="الله يعافيك، كيف حاسس اليوم؟"/>
        <x-dialog-line speaker="مريض" arb="مش تمام، أنا حاسس ________"/>
        <x-dialog-line speaker="دكتور" arb="سلامتك، شو مالك؟"/>
        <x-dialog-line speaker="مريض" arb="عندي ________ وحاسس بوجع بالـ________"/>
        <x-dialog-line speaker="دكتور" arb="تمام، بدّك دوا للوجع — فيه عندنا بالصيدليّة"/>
        <x-dialog-line speaker="مريض" arb="ممتاز، شكرا"/>
        <x-dialog-line speaker="دكتور" arb="ولو"/>
    </div>
</x-activity-area>
