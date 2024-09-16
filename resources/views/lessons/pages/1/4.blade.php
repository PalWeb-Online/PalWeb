<x-deck-container :deck="\App\Models\Deck::find(41)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">
    <p>When referring to humans & animals, many languages — like English & Arabic — use unique gendered terms (e.g. <b>"boy"</b>
        vs. <b>"girl"</b>). But while many animate nouns are gender-neutral in English (e.g. <b>"student"</b>), terms
        that refer to humans are essentially always gendered in Arabic. If a distinct feminine term doesn't exist, the
        feminine form is created by adding <b>ة (-e)</b> to the masculine. Although the plural forms of
        these nouns are built from the masculine too, the plural itself is gender-neutral.</p>
    <x-inflections
        conjM="ولد" conjMtr="boy"
        conjF="بنت" conjFtr="girl"
        conjP="ولاد" conjPtr="children"
    ></x-inflections>
    <x-inflections
        conjM="طالب" conjMtr="student"
        conjF="طالبة" conjFtr="student"
        conjP="طلّاب" conjPtr="students"
    ></x-inflections>

    <p>In English, the pronouns <b>"he"</b> & <b>"she"</b> must match the gender of the person they refer to, with <b>"it"</b>
        being reserved for inanimate nouns. In Arabic, though, all nouns — animate or not — are gendered, meaning that
        even non-human nouns are either <b>"he"</b> or <b>"she"</b>!</p>

    <x-sentence-item eng="she is Sama">
        <x-sentence-term arb="هي" eng="she" :term="$terms['hiyye'] ?? null"/>
        <x-sentence-term arb="سما" eng="Sama"/>
    </x-sentence-item>

    <div class="array">
        <x-sentence-item eng="it's in Palestine">
            <x-sentence-term arb="هي" eng="she" :term="$terms['hiyye'] ?? null"/>
            <x-sentence-term arb="بـ" eng="in" :term="$terms['b-'] ?? null"/>
            <x-sentence-term arb="ـفلسطين" eng="Palestine" :term="$terms['falasṭīn'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="where is Jerusalem?">
            <x-sentence-term arb="وين" eng="where" :term="$terms['wēn'] ?? null"/>
            <x-sentence-term arb="القدس" eng="Jerusalem" :term="$terms['l-ʔuds'] ?? null"/>
        </x-sentence-item>
    </div>

    <p>How would you know whether an inanimate noun is masculine or feminine? In most cases, feminine nouns are
        distinct by their ending in <b>ة (-e)</b>. Only a handful of nouns break this rule: for instance, all
        cities & countries are feminine; others are simply irregular.</p>

</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

    <p>In Arabic, this distinction applies to the <b>Demonstrative Pronouns</b> as well: there are distinct masculine &
        feminine forms of <b>"this"</b>, alongside a single gender-neutral <b>"these"</b>.</p>
    <div class="array">
        <x-sentence-item eng="this is a school">
            <x-sentence-term arb="هاذي" eng="this.F" :term="$terms['hādi'] ?? null"/>
            <x-sentence-term arb="مدرسة" eng="school.F" :term="$terms['madrase'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="this is an office">
            <x-sentence-term arb="هاذا" eng="this.M" :term="$terms['hāda'] ?? null"/>
            <x-sentence-term arb="مكتب" eng="office.M" :term="$terms['maktab'] ?? null"/>
        </x-sentence-item>
    </div>
    <x-sentence-item eng="these are Sama & Dana">
        <x-sentence-term arb="هذول" eng="these" :term="$terms['hādōl'] ?? null"/>
        <x-sentence-term arb="سما" eng="Sama"/>
        <x-sentence-term arb="و" eng="&" :term="$terms['w'] ?? null"/>
        <x-sentence-term arb="دانا" eng="Dana"/>
    </x-sentence-item>

    <p><b>Demonstratives</b> are used as <b>Determiners</b> (i.e. "<b>this</b> house") by attaching them to the head
        noun of the phrase. Since the noun in this construction is necessarily definite, the article <b>الـ (-l)</b> is
        always used.</p>

    <x-sentence-item eng="this is a guy">
        <x-sentence-term arb="هاذا" eng="this.M" :term="$terms['hāda'] ?? null"/>
        <x-sentence-term arb="شبّ" eng="guy" :term="$terms['šabb'] ?? null"/>
    </x-sentence-item>

    <div class="array">
        <x-sentence-item eng="this guy is ...">
            <x-sentence-term arb="هاذا" eng="this.M" :term="$terms['hāda'] ?? null"/>
            <x-sentence-term arb="الشبّ" eng="the-guy" :term="$terms['šabb'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="this is the guy">
            <x-sentence-term arb="هاذا" eng="this.M" :term="$terms['hāda'] ?? null"/>
            <x-sentence-term arb="الشبّ" eng="the-guy" :term="$terms['šabb'] ?? null"/>
        </x-sentence-item>
    </div>

    <p>Notice that — on its own — this construction could be read either as a complete sentence or as a noun phrase.
        However, in a full sentence only one interpretation would be possible. Besides, it's common to abbreviate the
        <b>Demonstratives</b> to <b>هـ (ha-)</b> in these cases.</p>

    <x-sentence-item eng="this guy is in the class">
        <x-sentence-term arb="هاذا" eng="this.M" :term="$terms['hāda'] ?? null"/>
        <x-sentence-term arb="الشبّ" eng="the-guy" :term="$terms['šabb'] ?? null"/>
        <x-sentence-term arb="بـ" eng="in" :term="$terms['b-'] ?? null"/>
        <x-sentence-term arb="ـالصفّ" eng="the-class" :term="$terms['ṣaff'] ?? null"/>
    </x-sentence-item>

    <x-sentence-item eng="this guy is in the class">
        <x-sentence-term arb="هـ" eng="this" :term="$terms['ha-'] ?? null"/>
        <x-sentence-term arb="ـالشبّ" eng="the-guy" :term="$terms['šabb'] ?? null"/>
        <x-sentence-term arb="بـ" eng="in" :term="$terms['b-'] ?? null"/>
        <x-sentence-term arb="ـالصفّ" eng="the-class" :term="$terms['ṣaff'] ?? null"/>
    </x-sentence-item>

    <x-inflections
        conjM="هاذا" conjMtr="this"
        conjF="هاذي" conjFtr="this"
        conjP="هذول" conjPtr="these"
    ></x-inflections>
</x-lesson-concept>

<x-activity-area title="{{ __('exercise') }}">
    <p>Consider the following information about Arabic literature students at Birzeit University & use it to
        complete the following exercises.</p>
    <div class="array">
        <div class="activity-info-container">
            <div>الشبّ</div>
            <div>البلد</div>

            <div>أكرم</div>
            <div>ريحا</div>

            <div>غسّان</div>
            <div>نابلس</div>

            <div>شادي</div>
            <div>يافا</div>

            <div>جريس</div>
            <div>القدس</div>
        </div>

        <div class="activity-info-container">
            <div>الصبيّة</div>
            <div>البلد</div>

            <div>يارا</div>
            <div>غزّة</div>

            <div>دانا</div>
            <div>حيفا</div>

            <div>سما</div>
            <div>بيت لحم</div>

            <div>ميرا</div>
            <div>الناصرة</div>
        </div>
    </div>

    <p>{{ __('activity.true-false') }} If false, make the statement true.</p>
    <div class="activity-dialog">
        <x-dialog-line speaker="سؤال" arb="صحّ ولّا غلط — جريس صبيّة" eng="true or false — Jeries is a young lady"/>
        <x-dialog-line speaker="جواب" arb="غلط، جريس شبّ" eng="false, Jeries is a young man"/>
    </div>

    <div class="question-group shuffle">
        <x-activity-mc que="أكرم شبّ" ans="a"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="غسّان صبيّة" ans="b"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="شادي شبّ" ans="a"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="يارا شبّ" ans="b"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="دانا صبيّة" ans="a"
                       a="صحّ"
                       b="غلط"
        />
        <x-activity-mc que="سما شبّ" ans="b"
                       a="صحّ"
                       b="غلط"
        />
    </div>

    <p>{{ __('activity.multiple-choice') }} State your response as a full sentence.</p>
    <div class="activity-dialog">
        <x-dialog-line speaker="سؤال" arb="محمّد ويوسف شباب ولّا صبايا؟" eng="Mhammad & Yousef are guys or girls?"/>
        <x-dialog-line speaker="جواب" arb="همّه شباب" eng="they are guys"/>
    </div>

    <div class="question-group shuffle">
        <x-activity-mc que="أكرم وغسّان شباب ولّا صبايا؟" ans="a"
                       a="شباب"
                       b="صبايا"
        />
        <x-activity-mc que="يارا ودانا شباب ولّا صبايا؟" ans="b"
                       a="شباب"
                       b="صبايا"
        />
        <x-activity-mc que="شادي وجريس شباب ولّا صبايا؟" ans="a"
                       a="شباب"
                       b="صبايا"
        />
        <x-activity-mc que="سما وميرا شباب ولّا صبايا؟" ans="b"
                       a="شباب"
                       b="صبايا"
        />
    </div>

    <p>{{ __('activity.multiple-choice') }} State your response as a full sentence.</p>
    <div class="activity-dialog">
        <x-dialog-line speaker="سؤال" arb="مين هاذي؟" eng="who is this?"/>
        <x-dialog-line speaker="جواب" arb="هاذي سما" eng="this is Sama"/>
    </div>

    <div class="question-group shuffle">
        <x-activity-mc que="مين هاذي؟" ans="a" shuffle
                       a="ميرا"
                       b="جريس"
                       c="مدرسة"
        />
        <x-activity-mc que="مين هاذا؟" ans="b" shuffle
                       a="سما"
                       b="شادي"
                       c="مكتب"
        />
        <x-activity-mc que="هاذي مين؟" ans="a" shuffle
                       a="يارا"
                       b="أكرم"
                       c="مدرسة"
        />
        <x-activity-mc que="هاذا مين؟" ans="b" shuffle
                       a="دانا"
                       b="غسّان"
                       c="مكتب"
        />
        <x-activity-mc que="شو هاذا؟" ans="c" shuffle
                       a="يارا"
                       b="أكرم"
                       c="مكتب"
        />
        <x-activity-mc que="هاذي شو؟" ans="c" shuffle
                       a="سما"
                       b="شادي"
                       c="مدرسة"
        />
        <x-activity-mc que="مين هذول؟" ans="c" shuffle
                       a="دانا"
                       b="غسّان"
                       c="دانا وغسّان"
        />
        <x-activity-mc que="هذول مين؟" ans="c" shuffle
                       a="ميرا"
                       b="جريس"
                       c="ميرا وجريس"
        />
    </div>
</x-activity-area>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">
    <p>Palestinian Arabic is very lenient in terms of politeness registers. Politeness is a matter of
        demeanor, rather than form. Still, there a few easy strategies to sound more polite. One way is to
        address others initially by their name:</p>
    <x-sentence-item eng="where are you (Mera) from?">
        <x-sentence-term arb="من" eng="from" :term="$terms['min'] ?? null"/>
        <x-sentence-term arb="وين" eng="where" :term="$terms['wēn'] ?? null"/>
        <x-sentence-term arb="ميرا؟" eng="Mera"/>
    </x-sentence-item>

    <p>When trying to get someone's attention or
        addressing someone in general — especially in greetings — it's natural to use the
        vocative particle <b>يا (ya)</b>. Not using it can sound pretty abrupt.</p>
    <x-sentence-item eng="good morning, girls">
        <x-sentence-term arb="صباح الخير" eng="good morning" :term="$terms['ṣabāħ l-xēr'] ?? null"/>
        <x-sentence-term arb="يا" eng="oh" :term="$terms['ya'] ?? null"/>
        <x-sentence-term arb="صبايا" eng="girls" :term="$terms['ṣabiyye'] ?? null"/>
    </x-sentence-item>
</x-lesson-concept>

<x-activity-area title="{{ __('dialogue') }}">
    <p>Shadow the following conversation. Yara & Mera were introducing themselves to each other when a friend of
        Mera's passes by:</p>

    <div class="activity-dialog">
        <x-dialog-line speaker="يارا" arb="مرحبا، أنا يارا"/>
        <x-dialog-line speaker="ميرا" arb="مرحبتين، أنا ميرا — تشرّفنا"/>
        <x-dialog-line speaker="يارا" arb="إلي الشرف يا ميرا"/>
        <x-dialog-line speaker="ميرا" arb="من وين يارا؟"/>
        <x-dialog-line speaker="يارا" arb="من غزّة — وإنتي؟"/>
        <x-dialog-line speaker="ميرا" arb="أهلا وسهلا — أنا من الناصرة"/>
        <x-dialog-line speaker="يارا" arb="أهلين أهلين"/>
        <x-dialog-line speaker="سما" arb="مسا الخير يا صبايا"/>
        <x-dialog-line speaker="ميرا" arb="أهلين، مسا الورد"/>
        <x-dialog-line speaker="يارا" arb="مسا النور"/>
        <x-dialog-line speaker="ميرا" arb="سما، هاذي يارا"/>
        <x-dialog-line speaker="سما" arb="تشرّفنا، أنا سما"/>
        <x-dialog-line speaker="يارا" arb="إلي الشرف يا سما"/>
        <x-dialog-line speaker="ميرا" arb="يارا من غزّة"/>
        <x-dialog-line speaker="سما" arb="والله — أهلا وسهلا"/>
        <x-dialog-line speaker="يارا" arb="أهلين — وسما من وين؟"/>
        <x-dialog-line speaker="سما" arb="أنا من بيت لحم"/>
        <x-dialog-line speaker="يارا" arb="أهلا وسهلا"/>
    </div>

    <x-activity-mc que="مين من غزّة؟" ans="a"
                   a="يارا"
                   b="ميرا"
                   c="سما"
    />
    <x-activity-mc que="مين من الناصرة؟" ans="b"
                   a="يارا"
                   b="ميرا"
                   c="سما"
    />
    <x-activity-mc que="مين من بيت لحم؟" ans="c"
                   a="يارا"
                   b="ميرا"
                   c="سما"
    />

    <p>Consider the following information about students at Birzeit University. Some of them study Arabic
        literature, while others study English literature. Use the template to ask about other students in your
        class:</p>
    <div class="array">
        <div class="activity-info-container">
            <div>الشبّ</div>
            <div>الصفّ</div>

            <div>أكرم</div>
            <div>عربي</div>

            <div>غسّان</div>
            <div>عربي</div>

            <div>شادي</div>
            <div>عربي</div>

            <div>جريس</div>
            <div>عربي</div>

            <div>محمّد</div>
            <div>إنجليزي</div>

            <div>يوسف</div>
            <div>إنجليزي</div>
        </div>

        <div class="activity-info-container">
            <div>الصبيّة</div>
            <div>الصفّ</div>

            <div>يارا</div>
            <div>عربي</div>

            <div>دانا</div>
            <div>عربي</div>

            <div>سما</div>
            <div>عربي</div>

            <div>ميرا</div>
            <div>عربي</div>

            <div>لمى</div>
            <div>إنجليزي</div>

            <div>أروى</div>
            <div>إنجليزي</div>
        </div>
    </div>

    <div class="activity-dialog">
        <x-dialog-line speaker="يارا" arb="يا غسّان، مين الـ________ بالصفّ؟"/>
        <x-dialog-line speaker="غسّان" arb="الـ________؟ همّه ________، ________ و________"/>
        <x-dialog-line speaker="يارا" arb="________؟ مين ________؟"/>
        <x-dialog-line speaker="غسّان" arb="____ ________ بالصفّ"/>
        <x-dialog-line speaker="يارا" arb="عن جدّ؟ من وين ____؟"/>
        <x-dialog-line speaker="غسّان" arb="____ من ________"/>
        <x-dialog-line speaker="يارا" arb="تمام، و________؟ ____ مش بالصفّ؟"/>
        <x-dialog-line speaker="غسّان" arb="لأ، والله"/>
    </div>
</x-activity-area>
