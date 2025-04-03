<x-vue.deck component="DeckContainer" :deck="\App\Models\Deck::find(47)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>We've learned how to use <b>adjectives</b> as modifiers. Using <b>nouns</b> as modifiers is just as easy.</p>
    <x-sentence-item eng="a small bag">
        <x-sentence-term arb="كيس" eng="bag" :term="$terms['kīs'] ?? null"/>
        <x-sentence-term arb="زغير" eng="small" :term="$terms['zḡīr'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="a trash bag">
        <x-sentence-term arb="كيس" eng="bag" :term="$terms['kīs'] ?? null"/>
        <x-sentence-term arb="زبالة" eng="trash" :term="$terms['zbāle'] ?? null"/>
    </x-sentence-item>
    <p>We call this use of a noun to modify another noun <b>إضافة (ʔiḍāfa, "attachment")</b>. Just as we can modify a
        noun with multiple adjectives, we can modify a noun with multiple nouns as well.</p>
    <x-sentence-item eng="a new white house">
        <x-sentence-term arb="بيت" eng="house" :term="$terms['bēt'] ?? null"/>
        <x-sentence-term arb="أبيض" eng="white" :term="$terms['ʔabyaḍ'] ?? null"/>
        <x-sentence-term arb="جديد" eng="new" :term="$terms['ždīd'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="a university professor's house">
        <x-sentence-term arb="بيت" eng="house" :term="$terms['bēt'] ?? null"/>
        <x-sentence-term arb="أستاذ" eng="professor" :term="$terms['ʔustāz'] ?? null"/>
        <x-sentence-term arb="جامعة" eng="university" :term="$terms['žāmʕa'] ?? null"/>
    </x-sentence-item>

    <p>Keep in mind that <b>ة (-e)</b> in the <b>construct form</b> of nouns (i.e. all terms in <b>ʔiḍāfa</b>, except
        for the last) changes to <b>ة (-t)</b>.</p>
    <div class="array">
        <x-sentence-item eng="a bottle of water">
            <x-sentence-term arb="قنّينة" eng="ʔannīnt" :term="$terms['ʔannīne'] ?? null"/>
            <x-sentence-term arb="ميّ" eng="mayy" :term="$terms['mayy'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="a bottle">
            <x-sentence-term arb="قنّينة" eng="ʔannīne" :term="$terms['ʔannīne'] ?? null"/>
        </x-sentence-item>
    </div>

    <p>Otherwise, the only difference between adjectives & nouns as modifiers is that, while adjectives are universally
        subject to definiteness agreement with the head noun, only the final noun modifier is marked as definite.</p>
    <x-sentence-item eng="the new white house">
        <x-sentence-term arb="البيت" eng="the-house" :term="$terms['bēt'] ?? null"/>
        <x-sentence-term arb="الأبيض" eng="the-white" :term="$terms['ʔabyaḍ'] ?? null"/>
        <x-sentence-term arb="الجديد" eng="the-new" :term="$terms['ždīd'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="the university professor's house">
        <x-sentence-term arb="بيت" eng="house" :term="$terms['bēt'] ?? null"/>
        <x-sentence-term arb="أستاذ" eng="professor" :term="$terms['ʔustāz'] ?? null"/>
        <x-sentence-term arb="الجامعة" eng="the-university" :term="$terms['žāmʕa'] ?? null"/>
    </x-sentence-item>

    <p>While the <b>head</b> of the structure seems overtly indefinite, this is not actually the case: it lacks the
        article <b>الـ</b> because it's defined by the <b>modifier</b> itself; the entire phrase is definite as a whole.
    </p>

    <p>Just as we can chain many <b>adjectives</b> to modify one noun, we can nest one <b>ʔiḍāfa</b> phrase as the <b>head</b>
        of another — but don't forget that the <b>head</b> of an <b>ʔiḍāfa</b> phrase is always indefinite!</p>

    <x-sentence-item eng="what is the meaning of the word 'bisse'?">
        <x-sentence-term arb="شو" eng="what" :term="$terms['šu'] ?? null"/>
        <x-sentence-term arb="معنى" eng="meaning" :term="$terms['maʕna'] ?? null"/>
        <x-sentence-term arb="كلمة" eng="word" :term="$terms['kilme'] ?? null"/>
        <x-sentence-term arb="بسّة؟" eng="'bisse'" :term="$terms['bisse'] ?? null"/>
    </x-sentence-item>

</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

    <p>Most of the time, using prepositions is very straightforward in Palestinian Arabic, as
        they are used far
        more literally than in English. In fact, while there are over 150 prepositions in English, in
        Palestinian Arabic
        there are only about 30; a single one like <b>من (min)</b> convers the senses of place (<b>"from"</b>),
        time (<b>"since"</b>) & quality (<b>"than"</b>).</p>
    <x-sentence-item eng="I live near Jerusalem">
        <x-sentence-term arb="أنا" eng="I" :term="$terms['ʔana'] ?? null"/>
        <x-sentence-term arb="ساكن" eng="living" :term="$terms['sākin'] ?? null"/>
        <x-sentence-term arb="قريب" eng="near" :term="$terms['ʔarīb'] ?? null"/>
        <x-sentence-term arb="من" eng="from" :term="$terms['min'] ?? null"/>
        <x-sentence-term arb="القدس" eng="Jerusalem" :term="$terms['l-ʔuds'] ?? null"/>
    </x-sentence-item>
    <p>Still, sometimes certain expressions use prepositions in ways that doesn't translate very well
        literally. While <b>من (min)</b> is the default word for <b>"from"</b>, for instance, the word
        <b>عن (ʕan "about")</b> may actually be used this way as well — specifically if something is
        <b>"(away) from"</b> something else.</p>
    <x-sentence-item eng="I live far from Haifa">
        <x-sentence-term arb="أنا" eng="I" :term="$terms['ʔana'] ?? null"/>
        <x-sentence-term arb="ساكن" eng="living" :term="$terms['sākin'] ?? null"/>
        <x-sentence-term arb="بعيد" eng="far" :term="$terms['bʕīd'] ?? null"/>
        <x-sentence-term arb="عن" eng="from" :term="$terms['ʕan'] ?? null"/>
        <x-sentence-term arb="حيفا" eng="Haifa" :term="$terms['ħēfa'] ?? null"/>
    </x-sentence-item>
    <p>In fact, this holds in all situations that imply a distancing from something. As we'll learn later on,
        <b>من (min)</b> is used for making comparisons, while <b>عن (ʕan)</b> is used to express a preference:
    </p>
    <x-sentence-item eng="this is better than this">
        <x-sentence-term arb="هادا" eng="this" :term="$terms['hāda'] ?? null"/>
        <x-sentence-term arb="أحسن" eng="better" :term="$terms['ʔaħsan'] ?? null"/>
        <x-sentence-term arb="من" eng="than" :term="$terms['min'] ?? null"/>
        <x-sentence-term arb="هادا" eng="this" :term="$terms['hāda'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="I prefer this over this">
        <x-sentence-term arb="بفضّل" eng="I prefer" :term="$terms['faḍḍal'] ?? null"/>
        <x-sentence-term arb="هادا" eng="this" :term="$terms['hāda'] ?? null"/>
        <x-sentence-term arb="عن" eng="over" :term="$terms['ʕan'] ?? null"/>
        <x-sentence-term arb="هادا" eng="this" :term="$terms['hāda'] ?? null"/>
    </x-sentence-item>
    <p>As you can see, <b>من (min)</b> & <b>عن (ʕan)</b> are very similar, but they are not really
        interchangeable & each has its own use context. When it comes to synonyms like these, it's best
        not to memorize their meanings, but rather to practice using them in realistic settings.</p>
</x-lesson-concept>

<x-activity-area title="{{ __('exercise') }}">
    <p>Consider the following information about your friends' work schedules & use it to complete the following
        exercises.</p>
    <div class="array">
        <div class="activity-info-container" style="grid-template-columns: repeat(6, 1fr);">
            <div>الموظّف</div>
            <div>الأحد</div>
            <div>الإثنين</div>
            <div>الثلاثا</div>
            <div>الأربعا</div>
            <div>الخميس</div>

            <div>أكرم</div>
            <div>شغّال</div>
            <div>شغّال</div>
            <div>شغّال</div>
            <div style="grid-row: span 4">عطلة</div>
            <div>معطّل</div>

            <div>غسّان</div>
            <div>شغّال</div>
            <div>معطّل</div>
            <div>شغّال</div>
            <div>شغّال</div>

            <div>شادي</div>
            <div>معطّل</div>
            <div>شغّال</div>
            <div>شغّال</div>
            <div>شغّال</div>

            <div>جريس</div>
            <div>شغّال</div>
            <div>شغّال</div>
            <div>معطّل</div>
            <div>شغّال</div>
        </div>
    </div>

    <p>{{ __('activity.true-false') }}</p>
    <div class="question-group shuffle">
        <x-activity-mc que="أكرم شغّال يوم الثلاثا" ans="a"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="غسّان شغّال يوم الخميس" ans="a"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="شادي شغّال يوم الإثنين" ans="a"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="جريس شغّال يوم الأحد" ans="a"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="أكرم شغّال يوم الخميس" ans="b"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="غسّان شغّال يوم الإثنين" ans="b"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="شادي شغّال يوم الأحد" ans="b"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="جريس شغّال يوم الثلاثا" ans="b"
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
    <p>We've been learning a handful of simple ways to make a conversation flow more naturally. Let's try to learn a
        few
        more transition terms. While <b>المهمّ (l-muhimm "anyways")</b> — literally, <b>"the important thing"</b> —
        can
        help us refocus on key information while telling a story, <b>ع فكرة (ʕa fikra "in fact, actually; by the
            way")</b> allows us to
        interject with some relevant information.</p>

    <x-sentence-item eng="actually, Ghassan is sick today">
        <x-sentence-term arb="ع فكرة" eng="actually" :term="$terms['ʕa fikra'] ?? null"/>
        <x-sentence-term arb="غسّان" eng="Ghassan"/>
        <x-sentence-term arb="مريض" eng="sick" :term="$terms['mrīḍ'] ?? null"/>
        <x-sentence-term arb="اليوم" eng="today" :term="$terms['l-yōm'] ?? null"/>
    </x-sentence-item>

    <x-sentence-item eng="anyway, he's not coming to class">
        <x-sentence-term arb="المهمّ" eng="anyway" :term="$terms['l-muhimm'] ?? null"/>
        <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
        <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null"/>
        <x-sentence-term arb="جاي" eng="coming" :term="$terms['žāy'] ?? null"/>
        <x-sentence-term arb="عـ" eng="to" :term="$terms['ʕala'] ?? null"/>
        <x-sentence-term arb="ـالحصّة" eng="the-class" :term="$terms['ħiṣṣa'] ?? null"/>
    </x-sentence-item>
</x-lesson-concept>

<x-activity-area title="{{ __('dialogue') }}">
    <p>How many of these words do you know? Tell your conversation partner the meaning of any words you know;
        otherwise,
        ask them for the meaning.</p>

    <div class="array">
        <div class="activity-info-container" style="grid-template-columns: repeat(2, 1fr);">
            <div>عصفور</div>
            <div>لهجة</div>

            <div>ضوّ</div>
            <div>كسلان</div>

            <div>صورة</div>
            <div>فلّاح</div>

            <div>مشكلة</div>
            <div>قلب</div>
        </div>
    </div>

    <div class="dialog-body">
        <x-dialog-line speaker="جريس" arb="لحظة — مش فاهم"/>
        <x-dialog-line speaker="أكرم" arb="آه؟ شو؟"/>
        <x-dialog-line speaker="جريس" arb="شو معنى كلمة ________؟"/>
        <x-dialog-line speaker="أكرم" arb="________؟ هي ________ بالـ________"/>
    </div>
</x-activity-area>
