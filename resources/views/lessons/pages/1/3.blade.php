<x-deck-container :deck="\App\Models\Deck::find(31)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">
    <p>Negating nominal sentences in Arabic is very straightforward. We just insert the word <b>مش (miš "not")</b> after
        the subject, where we would expect the verb <b>"to be"</b> in English.</p>
    <x-sentence-item eng="you aren't Akram">
        <x-sentence-term arb="إنتا" eng="you.M" :term="$terms['ʔinta'] ?? null"/>
        <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null"/>
        <x-sentence-term arb="أكرم" eng="Akram"/>
    </x-sentence-item>
    <x-sentence-item eng="you aren't from Palestine">
        <x-sentence-term arb="إنتا" eng="you.M" :term="$terms['ʔinta'] ?? null"/>
        <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null"/>
        <x-sentence-term arb="من" eng="from" :term="$terms['min'] ?? null"/>
        <x-sentence-term arb="فلسطين" eng="Palestine" :term="$terms['falasṭīn'] ?? null"/>
    </x-sentence-item>

    <p>We can easily raise our intonation to turn these negative statements into questions. Alternatively, we can start
        the question with <b>مش (miš "not")</b> as well.</p>

    <x-sentence-item eng="you aren't from Palestine?">
        <x-sentence-term arb="إنتا" eng="you.M" :term="$terms['ʔinta'] ?? null"/>
        <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null"/>
        <x-sentence-term arb="من" eng="from" :term="$terms['min'] ?? null"/>
        <x-sentence-term arb="فلسطين؟" eng="Palestine" :term="$terms['falasṭīn'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="aren't you from Palestine?">
        <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null"/>
        <x-sentence-term arb="إنتا" eng="you.M" :term="$terms['ʔinta'] ?? null"/>
        <x-sentence-term arb="من" eng="from" :term="$terms['min'] ?? null"/>
        <x-sentence-term arb="فلسطين؟" eng="Palestine" :term="$terms['falasṭīn'] ?? null"/>
    </x-sentence-item>
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">
    <p>Talking about the weather in Arabic is easy, although it may seem strange at first how we do it. In English, we
        often use a <a href="https://en.wikipedia.org/wiki/Dummy_pronoun" target="_blank">dummy pronoun</a> to refer to
        the state of the world: We say <b>"it's raining"</b> — but what does <b>"it"</b> really refer to? In
        Arabic, <b>الدنيا (d-dinya)</b> — literally, <b>"the world"</b> — is the subject of sentences that refer to the
        state of the world, the weather, etc.</p>
    <x-sentence-item eng="it's daytime in Palestine">
        <x-sentence-term arb="الدنيا" eng="(it)" :term="$terms['dinya'] ?? null"/>
        <x-sentence-term arb="نهار" eng="daytime" :term="$terms['nhār'] ?? null"/>
        <x-sentence-term arb="بـ" eng="in" :term="$terms['b-'] ?? null"/>
        <x-sentence-term arb="ـفلسطين" eng="Palestine" :term="$terms['falasṭīn'] ?? null"/>
    </x-sentence-item>
    <p>Not only that, but nouns are used where in English an adjective would be expected:</p>
    <x-sentence-item eng="is it hot there?">
        <x-sentence-term arb="الدنيا" eng="(it)" :term="$terms['dinya'] ?? null"/>
        <x-sentence-term arb="شوب" eng="heat" :term="$terms['šōb'] ?? null"/>
        <x-sentence-term arb="هناك؟" eng="there" :term="$terms['hunāk'] ?? null"/>
    </x-sentence-item>
    <p>Now we can use the following terms to easily talk about the weather in Arabic!</p>
</x-lesson-concept>

<x-activity-area title="{{ __('exercise') }}">
    <p>Consider the following information about the weather in different parts of the world & use it to complete the
        following exercises.</p>
    <div class="array">
        <div class="activity-info-container" style="grid-template-columns: repeat(3, 1fr);">
            <div>المدينة</div>
            <div>الطقس</div>
            <div>TIME</div>

            <div>فانكوفر</div>
            <div>شتا</div>
            <div>ليل</div>

            <div>ساو باولو</div>
            <div>غيم</div>
            <div>ليل</div>

            <div>برشلونا</div>
            <div>شمس</div>
            <div>نهار</div>

            <div>القاهرة</div>
            <div>شمس</div>
            <div>نهار</div>

            <div>لاغوس</div>
            <div>شتا</div>
            <div>نهار</div>

            <div>طهران</div>
            <div>ثلج</div>
            <div>نهار</div>

            <div>شانغهاي</div>
            <div>ثلج</div>
            <div>ليل</div>

            <div>سيدني</div>
            <div>شتا</div>
            <div>ليل</div>
        </div>
    </div>

    <p>{{ __('activity.multiple-choice') }}</p>
    <div class="question-group shuffle">
        <x-activity-mc que="الدنيا بفانكوفر شتا ولّا ثلج؟" ans="a"
                       a="شتا"
                       b="ثلج"
        />
        <x-activity-mc que="الدنيا بساو باولو شمس ولّا غيم؟" ans="b"
                       a="شمس"
                       b="غيم"
        />
        <x-activity-mc que="الدنيا بالقاهرة شمس ولّا غيم؟" ans="a"
                       a="شمس"
                       b="غيم"
        />
        <x-activity-mc que="الدنيا بطهران شتا ولّا ثلج؟" ans="b"
                       a="شتا"
                       b="ثلج"
        />
    </div>

    <p>{{ __('activity.true-false') }} If false, negate the statement.</p>
    <div class="dialog-body">
        <x-dialog-line speaker="سؤال" arb="صحّ ولّا غلط — الدنيا شتا ببرشلونا"
                       eng="true or false — it's raining in Barcelona"/>
        <x-dialog-line speaker="جواب" arb="غلط، الدنيا مش شتا ببرشلونا" eng="false, it isn't raining in Barcelona"/>
    </div>
    <div class="question-group shuffle">
        <x-activity-mc que="الدنيا شمس ببرشلونا" ans="a"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="الدنيا شتا بلاغوس" ans="a"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="الدنيا غيم بشانغهاي" ans="b"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="الدنيا ثلج بسيدني" ans="b"
                       a="صحّ"
                       b="غلط"
        />
    </div>

    <p>Infer whether the following statements are true or false. If false, make the statement true.</p>
    <div class="dialog-body">
        <x-dialog-line speaker="سؤال" arb="صحّ ولّا غلط — الدنيا نهار بنيو يورك"
                       eng="true or false — it's daytime in New York"/>
        <x-dialog-line speaker="جواب" arb="غلط، الدنيا ليل بنيو يورك" eng="false, it's nighttime in New York"/>
    </div>
    <div class="question-group shuffle">
        <x-activity-mc que="الدنيا ليل بكوبا" ans="a"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="الدنيا ليل بباريس" ans="b"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="الدنيا نهار بالقدس" ans="a"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="الدنيا نهار بتوكيو" ans="b"
                       a="صحّ"
                       b="غلط"
        />
    </div>

    <p>{{ __('activity.complete-sentence') }}</p>
    <div class="question-group shuffle">
        <x-activity-fill
            que="كيف الطقس بفانكوفر؟"
            ans="الدنيا شتا"/>
        <x-activity-fill
            que="كيف الطقس بساو باولو؟"
            ans="الدنيا غيم"/>
        <x-activity-fill
            que="كيف الطقس ببرشلونا والقاهرة؟"
            ans="الدنيا شمس"/>

        <x-activity-fill
            que="كيف الطقس بلاغوس؟"
            ans="الدنيا شتا"/>
        <x-activity-fill
            que="كيف الطقس بطهران؟"
            ans="الدنيا ثلج"/>
        <x-activity-fill
            que="كيف الطقس بشانغهاي وبسيدني؟"
            ans="الدنيا ثلج بشانغهاي والدنيا شتا بسيدني"/>
    </div>
</x-activity-area>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">
    <p>Communication isn't just a matter of creating well-formed sentences. Fluency means naturally expressing our
        attitudes to the information being communicated in a conversation as well. When communicating information we're
        uncertain of, we might want to get some confirmation or agreement from the other person — right? In Arabic, we
        can use <b>صحّ (ṣaħħ "correct")</b> for this.</p>
    <x-sentence-item eng="it's hot, right?">
        <x-sentence-term arb="شوب" eng="heat" :term="$terms['šōb'] ?? null"/>
        <x-sentence-term arb="صحّ؟" eng="right" :term="$terms['ṣaħħ'] ?? null"/>
    </x-sentence-item>

    <p>If we feel pretty certain about a statement, we can end it with <b>ولّا (willa "or")</b> to double-check that
        information. Since this word is used to present mutually exclusive options, we imply that the other option is
        <b>"not"</b> — like saying <b>"isn't
            it?"</b></p>
    <x-sentence-item eng="you're Akram, aren't you?">
        <x-sentence-term arb="إنتا" eng="you.M" :term="$terms['ʔinta'] ?? null"/>
        <x-sentence-term arb="أكرم" eng="Akram"/>
        <x-sentence-term arb="ولّا؟" eng="or" :term="$terms['willa'] ?? null"/>
    </x-sentence-item>

    <p>Similarly, we can express surprise at interesting or surprising information using common expressions like <b>والله
            (wallah "really")</b> & <b>عن جدّ (ʕan jadd "really")</b>.</p>
    <x-sentence-item eng="really? you're not Akram?">
        <x-sentence-term arb="عن جدّ؟" eng="really" :term="$terms['ʕan jadd'] ?? null"/>
        <x-sentence-term arb="إنتا" eng="you.M" :term="$terms['ʔinta'] ?? null"/>
        <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null"/>
        <x-sentence-term arb="أكرم؟" eng="Akram"/>
    </x-sentence-item>
</x-lesson-concept>

<x-activity-area title="{{ __('dialogue') }}">
    <p>Shadow the following conversation. Jeries & Akram were standing together when Shadi sees them:</p>
    <div class="dialog-body">
        <x-dialog-line speaker="شادي" arb="مسا الخير — كيف الحال؟"/>
        <x-dialog-line speaker="جريس" arb="مسا النور — تمام حمدلله"/>
        <x-dialog-line speaker="أكرم" arb="مسا الورد — حمدلله شكرًا"/>
        <x-dialog-line speaker="شادي" arb="إنتا ... غسّان، صحّ؟ من ريحا؟"/>
        <x-dialog-line speaker="أكرم" arb="أنا؟ أنا من ريحا، بسّ أنا مش غسّان"/>
        <x-dialog-line speaker="شادي" arb="ولّا؟"/>
        <x-dialog-line speaker="جريس" arb="هو أكرم"/>
        <x-dialog-line speaker="شادي" arb="أكرم! صحّ، صحّ"/>
        <x-dialog-line speaker="أكرم" arb="وإنتا شادي، آه؟ من القدس؟"/>
        <x-dialog-line speaker="شادي" arb="آه، شادي — بسّ أنا مش من القدس"/>
        <x-dialog-line speaker="أكرم" arb="عن جدّ؟ مين من القدس؟"/>
        <x-dialog-line speaker="جريس" arb="أنا، هو من يافا"/>
    </div>

    <x-activity-mc que="مين من ريحا؟" ans="c" shuffle
                   a="شادي"
                   b="جريس"
                   c="أكرم"
                   d="غسّان"
    />
    <x-activity-mc que="شادي من وين؟" ans="c" shuffle
                   a="القدس"
                   b="ريحا"
                   c="يافا"
                   d="حيفا"
    />

    <p>Use the template to discuss the weather over the phone. One speaker is in Jericho; the other may be in any
        one of these cities:</p>
    <div class="array">
        <div class="activity-info-container" style="grid-template-columns: repeat(4, 1fr);">
            <div>فانكوفر</div>
            <div>برشلونا</div>
            <div>طهران</div>
            <div>شانغهاي</div>
        </div>
    </div>

    <div class="dialog-body">
        <x-dialog-line speaker="جريس" arb="ألو، مرحبا"/>
        <x-dialog-line speaker="شادي" arb="مرحبتين، كيف الحال؟"/>
        <x-dialog-line speaker="جريس" arb="الحمدلله، وإنتا؟ وين إنتا؟"/>
        <x-dialog-line speaker="شادي" arb="أنا تمام، أنا بـ________!"/>
        <x-dialog-line speaker="جريس" arb="والله؟ كيف الطقس هناك؟ ________ ولّا ________"/>
        <x-dialog-line speaker="شادي" arb="الدنيا ________ هون — وهناك بفلسطين كيف؟"/>
        <x-dialog-line speaker="جريس" arb="هون بريحا الدنيا ________"/>
        <x-dialog-line speaker="شادي" arb="عن جدّ؟"/>
        <x-dialog-line speaker="جريس" arb="آه، والله"/>
    </div>
</x-activity-area>
