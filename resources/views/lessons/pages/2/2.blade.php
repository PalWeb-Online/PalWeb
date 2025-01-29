<x-deck-container :deck="\App\Models\Deck::find(48)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>So when do we use the numeral <b>واحد (wāħad "one")</b>? Mainly as a pronoun, in place of a noun; hence, the
        gender of <b>واحد (wāħad)</b> should match that of the noun in question.</p>
    <x-sentence-item eng="may I have one (plate)">
        <x-sentence-term arb="ممكن" eng="possible" :term="$terms['mumkin'] ?? null"/>
        <x-sentence-term arb="واحد" eng="M.one" :term="$terms['wāħad'] ?? null"/>
    </x-sentence-item>

    <x-inflections
        conjM="واحد" conjMtr="wāħad"
        conjF="واحدة" conjFtr="wāħde"
    ></x-inflections>

    <p>In fact, the expression for <b>"several"</b> is simply <b>"more than one"</b>:</p>
    <x-sentence-item eng="there are several cups">
        <x-sentence-term arb="فيه" eng="there is" :term="$terms['fīh'] ?? null"/>
        <x-sentence-term arb="أكتر" eng="more" :term="$terms['ktīr'] ?? null"/>
        <x-sentence-term arb="من" eng="than" :term="$terms['min'] ?? null"/>
        <x-sentence-term arb="كاسة" eng="one cup" :term="$terms['kāse'] ?? null"/>
    </x-sentence-item>

    <p>Like <b>واحد (wāħad "one")</b>, the numeral <b>ثنين (tnēn "two")</b> is mainly used as a pronoun & must match the
        gender of the noun it replaces.</p>
    <x-sentence-item eng="may I have two (cups)">
        <x-sentence-term arb="ممكن" eng="possible" :term="$terms['mumkin'] ?? null"/>
        <x-sentence-term arb="ثنتين" eng="F.two" :term="$terms['tnēn'] ?? null"/>
    </x-sentence-item>

    <x-inflections
        conjM="ثنين" conjMtr="tnēn"
        conjF="ثنتين" conjFtr="tintēn"
    ></x-inflections>

    <p>In Arabic, the numbers from <b>1-10</b> are very important to know; it just takes some memorization to learn
        them. Notice that from <b>3-10</b> they all end in <b>ة (-e)</b>.</p>
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">
    <p>We ask for the time in Arabic as follows:</p>
    <x-sentence-item eng="what time is it?">
        <x-sentence-term arb="قدّيش" eng="how much" :term="$terms['ʔaddēš'] ?? null"/>
        <x-sentence-term arb="الساعة؟" eng="the-hour" :term="$terms['sāʕa'] ?? null"/>
    </x-sentence-item>
    <p>We answer in the following format, adding fractions with <b>و (w)</b> & subtracting them with <b>إلّا (ʔilla)</b>.
    </p>
    <x-sentence-item eng="it is 3:30">
        <x-sentence-term arb="الساعة" eng="the-hour" :term="$terms['sāʕa'] ?? null"/>
        <x-sentence-term arb="تلاتة" eng="three" :term="$terms['talāte'] ?? null"/>
        <x-sentence-term arb="و" eng="and" :term="$terms['w'] ?? null"/>
        <x-sentence-term arb="نصّ" eng="half" :term="$terms['nuṣṣ'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="it is 11:45">
        <x-sentence-term arb="الساعة" eng="the-hour" :term="$terms['sāʕa'] ?? null"/>
        <x-sentence-term arb="تنعش" eng="twelve" :term="$terms['tnāš'] ?? null"/>
        <x-sentence-term arb="إلّا" eng="except" :term="$terms['ʔilla'] ?? null"/>
        <x-sentence-term arb="ربع" eng="quarter" :term="$terms['rubuʕ'] ?? null"/>
    </x-sentence-item>
</x-lesson-concept>

<x-activity-area title="{{ __('exercise') }}">
    <p>Consider the following information about the working hours of various locations & use it to complete the
        following
        exercises.</p>
    <div class="array">
        <div class="activity-info-container" style="grid-template-columns: repeat(5, 1fr);">
            <div>المحلّ</div>
            <div>الأربعا</div>
            <div>الخميس</div>
            <div>الجمعة</div>
            <div>السبت</div>

            <div>المطعم</div>
            <div>2 — 9</div>
            <div>2 — 1</div>
            <div>2 — 1</div>
            <div>2 — 11</div>

            <div>محلّ الأواعي</div>
            <div>11 — 7</div>
            <div>11 — 7</div>
            <div>2 — 7</div>
            <div>2 — 7</div>

            <div>مكتب الشركة</div>
            <div>9 — 5</div>
            <div>9 — 5</div>
            <div>مسكّر</div>
            <div>مسكّر</div>

            <div>البنك</div>
            <div>9 — 3</div>
            <div>9 — 12</div>
            <div>مسكّر</div>
            <div>مسكّر</div>

            <div>المدرسة</div>
            <div>7 — 3</div>
            <div>7 — 3</div>
            <div>مسكّر</div>
            <div>مسكّر</div>
        </div>
    </div>

    <p>{{ __('activity.true-false') }}</p>
    <div class="question-group shuffle">
        <x-activity-mc que="المطعم فاتح عالساعة ٢ يوم السبت" ans="a"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="محلّ الأواعي فاتح عالساعة ٢ يوم السبت" ans="a"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="مكتب الشركة فاتح عالساعة ٢ يوم السبت" ans="a"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="البنك فاتح عالساعة ٢ يوم السبت" ans="a"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="المدرسة فاتح عالساعة ٢ يوم السبت" ans="a"
                       a="صحّ"
                       b="غلط"
        />

        <x-activity-mc que="المطعم فاتح عالساعة ٢ يوم السبت" ans="b"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="محلّ الأواعي فاتح عالساعة ٢ يوم السبت" ans="b"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="مكتب الشركة فاتح عالساعة ٢ يوم السبت" ans="b"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="البنك فاتح عالساعة ٢ يوم السبت" ans="b"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="المدرسة فاتح عالساعة ٢ يوم السبت" ans="b"
                       a="صحّ"
                       b="غلط"
        />

    </div>

    <p>{{ __('activity.complete-sentence') }}</p>
    <div class="dialog-body">
        <x-dialog-line speaker="سؤال" arb="أكرم أيمتى معطّل هالأسبوع؟" eng="when is Akram off this week?"/>
        <x-dialog-line speaker="جواب" arb="هو معطّل يوم الـ ... ويوم الـ ..." eng="he's off on ... & ..."/>
    </div>

    <div class="question-group shuffle">
        <x-activity-fill
            que="أكرم أيمتى معطّل هالأسبوع؟"
            ans="هو معطّل يوم الأربعا ويوم الخميس"/>
        <x-activity-fill
            que="غسّان أيمتى معطّل هالأسبوع؟"
            ans="هو معطّل يوم الإثنين ويوم الأربعا"/>
        <x-activity-fill
            que="شادي أيمتى معطّل هالأسبوع؟"
            ans="هو معطّل يوم الأحد ويوم الأربعا"/>
        <x-activity-fill
            que="جريس أيمتى معطّل هالأسبوع؟"
            ans="هو معطّل يوم الثلاثا ويوم الأربعا"/>
    </div>
</x-activity-area>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">
    <p>Imagine if you were asked: <b>"aren't you from America?"</b> If you were, what should the answer be?</p>
    <x-sentence-item eng="aren't you from America?">
        <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null"/>
        <x-sentence-term arb="إنتا" eng="you.M" :term="$terms['ʔinta'] ?? null"/>
        <x-sentence-term arb="من" eng="from" :term="$terms['min'] ?? null"/>
        <x-sentence-term arb="أميركا" eng="America" :term="$terms['ʔamērka'] ?? null"/>
    </x-sentence-item>
    <p>Wouldn't <b>"yes"</b> imply: <b>"yes, I'm not from America?"</b> In Palestinian Arabic, there is a word
        specifically for this situation; it's used to negate negative statements & questions. Although it's technically
        a negative word — like <b>لأ (laʔ)</b> — it turns negative statements into affirmative ones.</p>
    <x-sentence-item eng="no, I am">
        <x-sentence-term arb="مبلا" eng="mbala" :term="$terms['mbala'] ?? null"/>
    </x-sentence-item>
    <p>It's used a lot not just to answer questions, but to contradict negative statements in a conversation.</p>
    <div class="array">
        <x-sentence-item eng="yes, it is">
            <x-sentence-term arb="مبلا" eng="mbala" :term="$terms['mbala'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="Jerusalem isn't Palestine">
            <x-sentence-term arb="القدس" eng="Jerusalem" :term="$terms['l-ʔuds'] ?? null"/>
            <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null"/>
            <x-sentence-term arb="فلسطين" eng="Palestine" :term="$terms['falasṭīn'] ?? null"/>
        </x-sentence-item>
    </div>
</x-lesson-concept>

<x-activity-area title="{{ __('dialogue') }}">
    <p>Shadow the following conversation between Jiryis & Shadi:</p>
    <div class="dialog-body">
        <x-dialog-line speaker="شادي" arb="مسا الخير، كيف الحال؟"/>
        <x-dialog-line speaker="جريس" arb="مسا النور — تمام، وإنتا؟"/>
        <x-dialog-line speaker="شادي" arb="حمدلله"/>
    </div>

    <p>Respond to the following statements with the appropriate word:</p>
    <div class="question-group">
        <x-activity-mc que="الخليل مش بفلسطين" ans="b" shuffle
                       a="صحّ"
                       b="مبلا"
        />
        <x-activity-mc que="القاهرة مش بفلسطين" ans="a" shuffle
                       a="صحّ"
                       b="مبلا"
        />
    </div>
</x-activity-area>
