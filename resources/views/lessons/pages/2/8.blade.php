<x-deck-container :deck="\App\Models\Deck::find(54)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>You may have noticed that Arabic has a word like <b>"the"</b> — the definite article <b>الـ (l-)</b> — but no
        word like <b>"a"</b> or <b>"an"</b>. In Arabic, nouns without <b>الـ (l-)</b> are automatically
        indefinite. Also, since singular nouns always refer to <b>"one"</b> of something by definition, we rarely use
        the word <b>"one"</b> as a counter for an indefinite singular noun; it's redundant.</p>
    <div class="array">
        <x-sentence-item eng="one plate">
            <x-sentence-term arb="صحن" eng="(a) plate" :term="$terms['ṣaħn'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="one cup">
            <x-sentence-term arb="كاسة" eng="(a) cup" :term="$terms['kāse'] ?? null"/>
        </x-sentence-item>
    </div>

    <p>One of the unique features of Arabic, though, is the existence of a suffix — <b>ين (-ēn)</b> — that can
        apply to any countable noun to indicate two of that noun:</p>
    <x-sentence-item eng="two plates">
        <x-sentence-term arb="صحنين" eng="plate-two" :term="$terms['ṣaħn'] ?? null"/>
    </x-sentence-item>
    <p>Do you remember the <b>ة (-e)</b> that feminine nouns usually end in? It's shaped like that because it actually
        becomes a <b>تـ (-t)</b> when something is attached to it, including the dual suffix:</p>
    <x-sentence-item eng="two cups">
        <x-sentence-term arb="كاستين" eng="cup-two" :term="$terms['kāse'] ?? null"/>
    </x-sentence-item>

    <p>As for the numbers from <b>3–10</b>, we insert the counter before the noun — like in English (e.g. <b>"three
            glasses"</b>) — but we use its <b>construct form</b>, formed by removing the final <b>ة (-e)</b> of the
        numeral.</p>

    <x-sentence-item eng="may we have three glasses of water">
        <x-sentence-term arb="ممكن" eng="possible" :term="$terms['mumkin'] ?? null"/>
        <x-sentence-term arb="تلات" eng="three" :term="$terms['talāte'] ?? null"/>
        <x-sentence-term arb="كاسات" eng="cups" :term="$terms['kāse'] ?? null"/>
        <x-sentence-term arb="ميّ" eng="water" :term="$terms['mayy'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="the journey is three hours long">
        <x-sentence-term arb="مدّة" eng="duration" :term="$terms['mudde'] ?? null"/>
        <x-sentence-term arb="الرحلة" eng="the-journey" :term="$terms['riħle'] ?? null"/>
        <x-sentence-term arb="تلات" eng="three" :term="$terms['talāte'] ?? null"/>
        <x-sentence-term arb="ساعات" eng="hours" :term="$terms['sāʕa'] ?? null"/>
    </x-sentence-item>

    {{--    <p>Aside from <b>3-10</b>, the only other numeral set with construct forms is <b>11-19</b>; these are formed by--}}
    {{--        re-introducing the final <b>ر (-r)</b> in <b>عشرة (ʕašara)</b> (see below).</p>--}}

    {{--    <p>When counting past ten of a noun, we use its singular form:</p>--}}
    {{--    <x-sentence-item eng="the journey is thirteen hours long">--}}
    {{--        <x-sentence-term arb="مدّة" eng="duration" :term="$terms['mudde'] ?? null"/>--}}
    {{--        <x-sentence-term arb="الرحلة" eng="the-journey" :term="$terms['riħle'] ?? null"/>--}}
    {{--        <x-sentence-term arb="تلاتّعشر" eng="thirteen" :term="$terms['talāttāš'] ?? null"/>--}}
    {{--        <x-sentence-term arb="ساعة" eng="hour" :term="$terms['sāʕa'] ?? null"/>--}}
    {{--    </x-sentence-item>--}}
    {{--    <x-sentence-item eng="the journey is thirty hours long">--}}
    {{--        <x-sentence-term arb="مدّة" eng="duration" :term="$terms['mudde'] ?? null"/>--}}
    {{--        <x-sentence-term arb="الرحلة" eng="the-journey" :term="$terms['riħle'] ?? null"/>--}}
    {{--        <x-sentence-term arb="تلاتين" eng="thirty" :term="$terms['talātīn'] ?? null"/>--}}
    {{--        <x-sentence-term arb="ساعة" eng="hour" :term="$terms['sāʕa'] ?? null"/>--}}
    {{--    </x-sentence-item>--}}

    {{--    <p>We also use the singular form to say <b>"some"</b> or <b>"a few"</b>:</p>--}}
    {{--    <x-sentence-item eng="the journey takes a few hours">--}}
    {{--        <x-sentence-term arb="الرحلة" eng="the-journey" :term="$terms['riħle'] ?? null"/>--}}
    {{--        <x-sentence-term arb="بدّها" eng="needs" :term="$terms['bidd-'] ?? null"/>--}}
    {{--        <x-sentence-term arb="أكم" eng="a few" :term="$terms['ʔakam'] ?? null"/>--}}
    {{--        <x-sentence-term arb="ساعة" eng="hour" :term="$terms['sāʕa'] ?? null"/>--}}
    {{--    </x-sentence-item>--}}
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

    <p>We've already learned the term <b>كمان (kamān)</b> with the meaning of <b>"also"</b>, but this term is also used
        as a pronoun with the meaning of <b>"more"</b>. Let's practice using it alongside <b>حبّة (ħabbe)</b>, a generic
        term commonly used to refer to a single unit of something that is otherwise uncountable.</p>

    <x-sentence-item eng="I'd like some more coffee">
        <x-sentence-term arb="بدّي" eng="1S.want" :term="$terms['biddo'] ?? null"/>
        <x-sentence-term arb="كمان" eng="more" :term="$terms['kamān'] ?? null"/>
        <x-sentence-term arb="قهوة" eng="coffee" :term="$terms['ʔahwe'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="I'd like one more (can, etc.) of cola">
        <x-sentence-term arb="بدّي" eng="1S.want" :term="$terms['biddo'] ?? null"/>
        <x-sentence-term arb="كمان" eng="more" :term="$terms['kamān'] ?? null"/>
        <x-sentence-term arb="حبّة" eng="piece" :term="$terms['ħabb'] ?? null"/>
        <x-sentence-term arb="كولا" eng="cola" :term="$terms['kōla'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="may I have two more pieces of falafel">
        <x-sentence-term arb="ممكن" eng="possible" :term="$terms['mumkin'] ?? null"/>
        <x-sentence-term arb="كمان" eng="more" :term="$terms['kamān'] ?? null"/>
        <x-sentence-term arb="حبّتين" eng="piece-two" :term="$terms['ħabb'] ?? null"/>
        <x-sentence-term arb="فلافل" eng="falafel" :term="$terms['falāfil'] ?? null"/>
    </x-sentence-item>
</x-lesson-concept>

<x-activity-area title="{{ __('exercise') }}">

    <p>You & your roommate will be going grocery shopping, so you need to make a list of what you need from the
        store. Consider the following information about your kitchen inventory & use it to complete the following
        exercises.</p>
    <div class="array">
        <div class="activity-info-container" style="grid-template-columns: repeat(2, 1fr);">
            <div>فيه</div>
            <div>بدّكم</div>

            <div>ثلاث بيضات</div>
            <div>١٢</div>

            <div>تفّاحة</div>
            <div>٤</div>

            <div>خمس حبّات بندورة</div>
            <div>٦</div>

            <div>بردقانتين</div>
            <div>٦</div>
        </div>
    </div>

    <p>{{ __('activity.yes-no') }} If you need more, state how many.</p>
    <div class="dialog-body">
        <x-dialog-line speaker="سؤال" arb="بدّنا كمان بردقان؟" eng="do we need more oranges?"/>
        <x-dialog-line speaker="جواب" arb="آه، بدّنا كمان ٤" eng="yes, we need another 4"/>
    </div>

    <div class="question-group shuffle">
        <x-activity-mc que="بدّنا كمان بيض؟" ans="a"
                       a="آه"
                       b="لأ"
        />
        <x-activity-mc que="بدّنا كمان تفّاح؟" ans="a"
                       a="آه"
                       b="لأ"
        />
        <x-activity-mc que="بدّنا كمان بردقان؟" ans="a"
                       a="آه"
                       b="لأ"
        />
        <x-activity-mc que="بدّنا كمان جاج؟" ans="a"
                       a="آه"
                       b="لأ"
        />
    </div>
</x-activity-area>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">

    <p>One of the most common interjections in many dialects of Arabic is <b>خلص (xalaṣ)</b>. It has many translations,
        but is basically used to put an end to something, similar to <b>"stop it"</b> or <b>"that's enough"</b>.</p>

    <div class="dialog-body">
        <x-dialog-line speaker="أكرم" arb="بدّك كمان قهوة؟" eng="would you like some more coffee?"/>
        <x-dialog-line speaker="سما" arb="لأ خلص، شكرا" eng="no, that's enough, thank you"/>
    </div>

    <div class="dialog-body">
        <x-dialog-line speaker="أكرم" arb="بس أنا كثير جوعان" eng="but I'm really hungry"/>
        <x-dialog-line speaker="سما" arb="خلص، فش كمان أكل" eng="enough, there's no more food"/>
    </div>

    <p>When it comes to etiquette surrounding food, it's customary to say <b>صحّة (ṣaħħa)</b> when you find someone
        eating. If you are offered food in this scenario, it's customary to say <b>صحّة (ṣaħħa)</b> instead of saying
        <b>"no"</b> outright.</p>

    <div class="dialog-body">
        <x-dialog-line speaker="أكرم" arb="أنا بدّي قهوة ... إنتي بدّك؟"
                       eng="I want coffee ... would you like some?"/>
        <x-dialog-line speaker="سما" arb="صحّة، صحّة" eng="(no, thank you) enjoy yours"/>
    </div>

    <x-inflections
        grt="صحّة" grttr="ṣaħħa"
        rsp="ع قلبك" rsptr="ʕa ʔalbak"
    ></x-inflections>

</x-lesson-concept>
