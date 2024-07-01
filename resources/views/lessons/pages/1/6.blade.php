<x-deck :deck="\App\Models\Deck::find(43)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>In Arabic, adjectives come after the noun & match its gender & number. Echoing feminine nouns, the feminine forms
        of adjectives are formed by adding <b>ة (-e)</b>. Unlike the <b>broken plurals</b> of nouns, though, the plural
        forms of adjectives are formed regularly by adding <b>ين (-īn)</b>; these are <b>sound plurals</b>. With two
        exceptions, all
        adjectives have <b>sound plurals</b>; a few nouns are pluralized this way as well.</p>

    <div class="array">
        <x-sentence eng="he is busy">
            <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
            <x-sentence-term arb="مشغول" eng="busy.M" :term="$terms['mašġūl'] ?? null"/>
        </x-sentence>
        <x-sentence eng="she is busy">
            <x-sentence-term arb="هي" eng="she" :term="$terms['hiyye'] ?? null"/>
            <x-sentence-term arb="مشغولة" eng="busy.F" :term="$terms['mašġūl'] ?? null"/>
        </x-sentence>
    </div>
    <x-sentence eng="they are busy">
        <x-sentence-term arb="همّه" eng="they" :term="$terms['humme'] ?? null"/>
        <x-sentence-term arb="مشغولين" eng="busy.P" :term="$terms['mašġūl'] ?? null"/>
    </x-sentence>

    <x-inflections
        conjM="مشغول" conjMtr="mašġūl"
        conjF="مشغولة" conjFtr="mašġūle"
        conjP="مشغولين" conjPtr="mašġūlīn"
    ></x-inflections>

    <x-collapsible title="SEE MORE">
        <p>On rare occasion, you may find that an adjective doesn't vary in terms of gender & number. We've already
            learned three very common ones — did you notice?</p>
        <x-vocabulary title="adjectives">
            <x-term :term="$terms['tamām'] ?? null"/>
            <x-inflections
                conjM="تمام" conjMtr="tamām"
                conjF="تمام" conjFtr="tamām"
                conjP="تمام" conjPtr="tamām"
            ></x-inflections>
        </x-vocabulary>
        <x-vocabulary>
            <x-term :term="$terms['ṣaħħ'] ?? null"/>
            <x-term :term="$terms['ġalaṭ'] ?? null"/>
        </x-vocabulary>
    </x-collapsible>
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

    <p>We've learned that Arabic creates new terms primarily by placing roots in different
        patterns, but a few other strategies exist as well that are easier to pick up. We can use the suffix <b>يّ
            (-yy)</b>, for instance, to form adjectives out of nouns, especially proper nouns; these are called
        <b>relative adjectives</b>, because they indicate that something is related to that noun. Let's use
        this suffix now to create terms for various nationalities. What would yours be?</p>

    <div class="array">
        <x-sentence eng="Palestinian">
            <x-sentence-term arb="فلسطينيّ" eng="falasṭīni" :term="$terms['falasṭīni'] ?? null"/>
        </x-sentence>
        <x-sentence eng="Palestine">
            <x-sentence-term arb="فلسطين" eng="falasṭīn" :term="$terms['falasṭīn'] ?? null"/>
        </x-sentence>
    </div>
</x-lesson-concept>

<x-activity-area title="{{ __('exercise') }}">
    <p>Dana & Yara need to find a time to chat. Consider the following information about their day & use it to
        complete the following
        exercises.</p>
    <div class="array">
        <div class="activity-info-container" style="grid-template-columns: repeat(3, 1fr);">
            <div>TIME</div>
            <div>دانا</div>
            <div>يارا</div>

            <div>09:00</div>
            <div>حصّة</div>
            <div></div>

            <div>10:00</div>
            <div style="grid-column: span 2">إجتماع</div>

            <div>11:00</div>
            <div></div>
            <div>حصّة</div>

            <div>12:00</div>
            <div></div>
            <div></div>

            <div>13:00</div>
            <div style="grid-column: span 2">إمتحان</div>

            <div>14:00</div>
            <div>حصّة</div>
            <div>حصّة</div>
        </div>
    </div>

    <p>{{ __('activity.true-false') }} If false, negate the statement.</p>
    <div class="question-group shuffle">
        <x-activity-mc que="دانا فاضية قبل الإجتماع" ans="b"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="دانا فاضية بعد الإجتماع" ans="a"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="يارا مشغولة قبل الإمتحان" ans="b"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="يارا مشغولة بعد الإمتحان" ans="b"
                       a="صحّ"
                       b="غلط"
        />
    </div>
    <p>{{ __('activity.multiple-choice') }}</p>
    <div class="question-group shuffle">
        <x-activity-mc que="أيمتى فاضيين الصبيّتين؟" ans="c"
                       a="قبل الإجتماع"
                       b="بعد الإجتماع"
                       c="قبل الإمتحان"
                       d="بعد الإمتحان"
        />
        <x-activity-mc que="أيمتى مشغولين الصبيّتين؟" ans="d"
                       a="قبل الإجتماع"
                       b="بعد الإجتماع"
                       c="قبل الإمتحان"
                       d="بعد الإمتحان"
        />
    </div>

    <p>Consider the following information about everyone's availability & use it to complete the following
        exercises.</p>
    <div class="array">
        <div class="activity-info-container" style="grid-template-columns: repeat(5, 1fr);">
            <div style="grid-column: 2 / span 2">اليوم</div>
            <div style="grid-column: 4 / span 2">بكرا</div>

            <div>طالب</div>
            <div>قبل الحصّة</div>
            <div>بعد الحصّة</div>
            <div>قبل الحصّة</div>
            <div>بعد الحصّة</div>

            <div>أكرم</div>
            <div>فاضي</div>
            <div>مشغول</div>
            <div>فاضي</div>
            <div>فاضي</div>

            <div>سما</div>
            <div>مشغولة</div>
            <div>مشغولة</div>
            <div>فاضية</div>
            <div>مشغولة</div>

            <div>غسّان</div>
            <div>فاضي</div>
            <div>مشغول</div>
            <div>فاضي</div>
            <div>مشغول</div>

            <div>ميرا</div>
            <div>مشغولة</div>
            <div>مشغولة</div>
            <div>فاضية</div>
            <div>مشغولة</div>
        </div>
    </div>

    <p>{{ __('activity.multiple-choice') }}</p>
    <div class="question-group shuffle">
        <x-activity-mc que="أكرم فاضي قبل الحصّة اليوم؟" ans="a"
                       a="آه"
                       b="لأ"
        />
        <x-activity-mc que="بكرا أكرم مشغول بعد الحصّة؟" ans="b"
                       a="آه"
                       b="لأ"
        />
        <x-activity-mc que="ميرا مشغولة بعد الحصّة بكرا؟" ans="a"
                       a="آه"
                       b="لأ"
        />
        <x-activity-mc que="اليوم ميرا فاضية قبل الحصّة؟" ans="b"
                       a="آه"
                       b="لأ"
        />
    </div>
    <div class="question-group shuffle">
        <x-activity-mc que="اليوم سما فاضية ولّا مشغولة بعد الحصّة؟" ans="b"
                       a="فاضية"
                       b="مشغولة"
        />
        <x-activity-mc que="سما فاضية ولّا مشغولة قبل الحصّة بكرا؟" ans="a"
                       a="فاضية"
                       b="مشغولة"
        />
        <x-activity-mc que="بكرا غسّان فاضي ولّا مشغول قبل الحصّة؟" ans="a"
                       a="فاضي"
                       b="مشغول"
        />
        <x-activity-mc que="غسّان فاضي ولّا مشغول بعد الحصّة اليوم؟" ans="b"
                       a="فاضي"
                       b="مشغول"
        />
    </div>

    <p>{{ __('activity.complete-sentence') }}</p>
    <div class="question-group shuffle">
        <x-activity-fill
            que="اليوم أيمتى فاضي أكرم؟"
            ans="أكرم فاضي قبل الحصّة اليوم"/>
        <x-activity-fill
            que="أيمتى فاضية سما بكرا؟"
            ans="سما فاضية قبل الحصّة بكرا"/>
        <x-activity-fill
            que="أيمتى مشغول غسّان اليوم؟"
            ans="غسّان مشغول بعد الحصّة اليوم"/>
        <x-activity-fill
            que="بكرا أيمتى مشغولة ميرا؟"
            ans="ميرا مشغولة بعد الحصّة بكرا"/>
    </div>
</x-activity-area>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">

    <p>Sometimes we need to reassure others, whether to assuage their fears or to soften some bad news. We say things
        like <b>"it's fine"</b> when someone apologizes, or <b>"honestly"</b> to preface a hard truth. Let's practice
        doing the same thing in Arabic.</p>
    <p>When we want to tell someone not to worry or brush something off, we can use either <b>عادي (ʕādi)</b> — an
        adjective meaning <b>"normal"</b> — or <b>معليش (maʕlēš)</b>, both expressing the meaning of <b>"it's fine"</b>.
        We often use these in response to an apology.</p>
    <x-sentence eng="it's fine, the test is really easy">
        <x-sentence-term arb="عادي" eng="it's fine" :term="$terms['ʕādi-phrase'] ?? null"/>
        <x-sentence-term arb="الإمتحان" eng="the-exam" :term="$terms['ʔimtiħān'] ?? null"/>
        <x-sentence-term arb="كثير" eng="very" :term="$terms['ktīr'] ?? null"/>
        <x-sentence-term arb="سهل" eng="easy" :term="$terms['sihil'] ?? null"/>
    </x-sentence>
    <p>When we come bearing bad news, though, we can soften it with <b>بصراحة (b-ṣarāħa "honestly")</b>.</p>
    <x-sentence eng="honestly, the test is really hard">
        <x-sentence-term arb="بصراحة" eng="honestly" :term="$terms['ṣarāħa'] ?? null"/>
        <x-sentence-term arb="الإمتحان" eng="the-exam" :term="$terms['ʔimtiħān'] ?? null"/>
        <x-sentence-term arb="كثير" eng="very" :term="$terms['ktīr'] ?? null"/>
        <x-sentence-term arb="صعب" eng="difficult" :term="$terms['ṣiʕib'] ?? null"/>
    </x-sentence>
</x-lesson-concept>

<x-activity-area title="{{ __('dialogue') }}">
    <p>Shadow the following conversation. Shadi was studying the university cafeteria when Mera & Sama — & then
        Akram — pass by.</p>
    <div class="activity-dialog">
        <x-dialog-line speaker="شادي" arb="صباح الخير يا صبايا، كيف الحال؟"/>
        <x-dialog-line speaker="سما" arb="أهلين، صباح النور"/>
        <x-dialog-line speaker="ميرا" arb="إحنا تمام، وإنتا؟ مشغول كثير؟"/>
        <x-dialog-line speaker="شادي" arb="آه، الإمتحان بكرا وأنا مش جاهز"/>
        <x-dialog-line speaker="ميرا" arb="بكرا؟ مش اليوم؟"/>
        <x-dialog-line speaker="شادي" arb="لأ، بكرا ... ولّا؟"/>
        <x-dialog-line speaker="سما" arb="هو اليوم، شادي"/>
        <x-dialog-line speaker="شادي" arb="شو؟ عن جدّ؟"/>
        <x-dialog-line speaker="سما" arb="يعني آه، قبل الحصّة"/>
        <x-dialog-line speaker="شادي" arb="قبل الحصّة؟"/>
        <x-dialog-line speaker="سما" arb="عادي، الإمتحان مش صعب"/>
        <x-dialog-line speaker="ميرا" arb="يعني ..."/>
        <x-dialog-line speaker="شادي" arb="يعني؟"/>
        <x-dialog-line speaker="ميرا" arb="يعني، مش كثير صعب"/>
        <x-dialog-line speaker="أكرم" arb="مرحبا يا صبايا، شادي"/>
        <x-dialog-line speaker="شادي" arb="أكرم، الحمدلله — كيف الإمتحان؟"/>
        <x-dialog-line speaker="أكرم" arb="الإمتحان؟ ييييي — كثير صعب، بصراحة"/>
        <x-dialog-line speaker="سما" arb="كثير صعب؟ كيف يعني؟"/>
        <x-dialog-line speaker="أكرم" arb="والله، صعب — شو، مش جاهز؟"/>
        <x-dialog-line speaker="شادي" arb="لأ، بصراحة"/>
        <x-dialog-line speaker="أكرم" arb="طيّب ... هاذي مشكلة"/>
        <x-dialog-line speaker="شادي" arb="والله؟"/>
    </div>
    <x-activity-mc que="الإمتحان اليوم ولّا بكرا؟" ans="a"
                   a="اليوم"
                   b="بكرا"
    />
    <x-activity-mc que="أيمتى الإمتحان؟" ans="a"
                   a="قبل الحصّة"
                   b="بعد الحصّة"
    />

    <p>Use the template to discuss a test already taken by another class. <b>Person أ</b> hasn't taken the test yet
        & would like to know whether or not it's difficult. <b>Person ب</b> — one of the students in another group —
        has already taken the test; their answer should be based on their own score on the test, provided below.</p>
    <div class="array">
        <div class="activity-info-container">
            <div>طالب</div>
            <div>SCORE</div>
            <div>أكرم</div>
            <div>61</div>
            <div>غشّان</div>
            <div>86</div>
            <div>شادي</div>
            <div>72</div>
            <div>جريس</div>
            <div>64</div>
            <div>يارا</div>
            <div>93</div>
            <div>دانا</div>
            <div>90</div>
            <div>سما</div>
            <div>85</div>
            <div>ميرا</div>
            <div>77</div>
        </div>
    </div>

    <div class="activity-dialog">
        <x-dialog-line speaker="أ" arb="صباح الخير يا ________، كيف الحال؟"/>
        <x-dialog-line speaker="ب" arb="________، وإنتا؟ مشغول كتير؟"/>
        <x-dialog-line speaker="أ" arb="آه، الإمتحان بكرا وأنا مش جاهز"/>
        <x-dialog-line speaker="ب" arb="بكرا؟ مش اليوم؟"/>
        <x-dialog-line speaker="أ" arb="اليوم؟ عن جدّ؟"/>
        <x-dialog-line speaker="ب" arb="آه، ________"/>
        <x-dialog-line speaker="أ" arb="كيف الإمتحان، طيّب؟"/>
        <x-dialog-line speaker="ب" arb="________، الإمتحان ________"/>
    </div>
</x-activity-area>
