<x-vue.deck component="DeckContainer" :deck="\App\Models\Deck::find(49)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>We use <b>ʔidāfa</b> to — among other things — indicate possession; hence:</p>
    <x-sentence-item eng="the man's family">
        <x-sentence-term arb="عيلة" eng="family" :term="$terms['ʕēle'] ?? null"/>
        <x-sentence-term arb="الزلمة" eng="the-man" :term="$terms['zalame'] ?? null"/>
    </x-sentence-item>
    <p>But what if, instead of using a noun, we'd like to use a pronoun? How can we say <b>"his family"</b> or even <b>"my
            family"</b>? In theory, we'd like to use a pronoun instead of <b>الزلمة</b>. However, <b>هو</b> can't be
        used here.</p>
    <p>Arabic pronouns have two forms: a <b>noun form</b> for nouns in the <b>nominative</b> case (the default case of
        nouns) & a <b>clitic form</b> for nouns in the <b>genitive-accusative</b> case. In practical terms, the <b>clitic
            form</b> is the one used to indicate possession & — as we will learn later — to indicate verbal objects.</p>
    <x-sentence-item eng="his family">
        <x-sentence-term arb="عيلتـ" eng="family" :term="$terms['ʕēle'] ?? null"/>
        <x-sentence-term arb="ـه" eng="his" :term="$terms['-o'] ?? null"/>
    </x-sentence-item>

    <x-inflections
        conj1S="عيلتي" conj1Str="ʕēlti"
        conj1P="عيلتنا" conj1Ptr="ʕēltna"
        conj2M="عيلتك" conj2Mtr="ʕēltak"
        conj2F="عيلتك" conj2Ftr="ʕēltek"
        conj2P="عيلتكم" conj2Ptr="ʕēltkom"
        conj3M="عيلته" conj3Mtr="ʕēlto"
        conj3F="عيلتها" conj3Ftr="ʕēltha"
        conj3P="عيلتهم" conj3Ptr="ʕēlthom"
    ></x-inflections>
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

    <p>In Palestinian Arabic, final vowels are always short on the surface. If a suffix were to follow the word,
        however, that vowel would no longer be final & would therefore be pronounced as long:</p>
    <x-sentence-item eng="father">
        <x-sentence-term arb="أبو" eng="ʔabu" :term="$terms['ʔabu'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="her father">
        <x-sentence-term arb="أبوها" eng="ʔabūha" :term="$terms['ʔabu'] ?? null"/>
    </x-sentence-item>

    <p>Some <b>clitic pronouns</b>, however, begin with — or simply are — vowels. Palestinian Arabic doesn't allow two
        different vowels to exist back-to-back, so if we attach these clitic pronouns to a final vowel, they are going
        to change slightly.</p>

    <x-inflections
        conj1S="إمّي" conj1Str="ʔimmi"
        conj1P="إمّنا" conj1Ptr="ʔimmna"
        conj2M="إمّك" conj2Mtr="ʔimmak"
        conj2F="إمّك" conj2Ftr="ʔimmek"
        conj2P="إمّكم" conj2Ptr="ʔimmkom"
        conj3M="إمّه" conj3Mtr="ʔimmo"
        conj3F="إمّها" conj3Ftr="ʔimmha"
        conj3P="إمّهم" conj3Ptr="ʔimmhom"
    ></x-inflections>
    <x-inflections
        conj1S="أبوي" conj1Str="ʔabūy"
        conj1P="أبونا" conj1Ptr="ʔabūna"
        conj2M="أبوك" conj2Mtr="ʔabūk"
        conj2F="أبوكي" conj2Ftr="ʔabūki"
        conj2P="أبوكم" conj2Ptr="ʔabūkom"
        conj3M="أبوه" conj3Mtr="ʔabū(h)"
        conj3F="أبوها" conj3Ftr="ʔabūha"
        conj3P="أبوهم" conj3Ptr="ʔabūhom"
    ></x-inflections>
</x-lesson-concept>

<x-activity-area title="{{ __('exercise') }}">
    <p>Consider the following information & answer the questions accordingly:</p>

    <div class="array">
        <div class="activity-info-container">
            <div>أحمد عودة محمود البرغوثي</div>
            <div>مريم عمر أحمد الفار</div>

            <div>16/4/1993</div>
            <div>5/9/1989</div>

            <div>059-269-1957</div>
            <div>059-387-7651</div>
        </div>
    </div>

    <div class="question-group">
        <x-activity-fill
            que="شو إسم شخص هالزلمة؟"
            ans="أحمد"/>
        <x-activity-fill
            que="شو إسم عيلة هالزلمة؟"
            ans="البرغوثي"/>
        <x-activity-fill
            que="شو إسم هالزلمة الكامل؟"
            ans="أحمد عودة محمود البرغوثي"/>
        <x-activity-fill
            que="شو إسم شخص هالمرة؟"
            ans="مريم"/>
        <x-activity-fill
            que="شو إسم عيلة هالمرة؟"
            ans="الفار"/>
        <x-activity-fill
            que="شو إسم هالمرة الكامل؟"
            ans="مريم عمر أحمد الفار"/>
    </div>

    <div class="question-group">
        <div class="activity-fill">
            <p>إسم شخص هالزلمة</p>
        </div>
        <div class="activity-fill">
            <p>عيد ميلاد هالزلمة</p>
        </div>
        <div class="activity-fill">
            <p>عمر هالزلمة</p>
        </div>
        <div class="activity-fill">
            <p>رقم تلفون هالمرة</p>
        </div>
        <div class="activity-fill">
            <p>تاريخ ميلاد هالمرة</p>
        </div>
        <div class="activity-fill">
            <p>إسم هالمرة الكامل</p>
        </div>
    </div>
</x-activity-area>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">

    <p>We've learned that many phrases vary according to the gender of the addressee; namely, by featuring feminine
        verbal forms created by adding <b>ي (-i)</b>. In other cases, though, you may need to switch between <b>ـك
            (-ak)</b> & <b>ـك (-ek)</b> — different forms of <b>إنتا (ʔinta)</b> & <b>إنتي (ʔinti)</b> that will be
        discussed later.</p>

    <x-sentence-item eng="excuse me, could I get some help?">
        <x-sentence-term arb="أغلّبَك" eng="excuse me (m.)" :term="$terms['ʔaġallbak'] ?? null"/>
        <x-sentence-term arb="ممكن" eng="possible" :term="$terms['mumkin'] ?? null"/>
        <x-sentence-term arb="مساعدة" eng="help" :term="$terms['musāʕade'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="excuse me, could I get some help?">
        <x-sentence-term arb="أغلّبِك" eng="excuse me (f.)" :term="$terms['ʔaġallbak'] ?? null"/>
        <x-sentence-term arb="ممكن" eng="possible" :term="$terms['mumkin'] ?? null"/>
        <x-sentence-term arb="مساعدة" eng="help" :term="$terms['musāʕade'] ?? null"/>
    </x-sentence-item>

    <p>As you can see, <b>أغلّبك (ʔaġallbak/ek)</b> is an interjection used to get someone's attention when you'd like
        to ask something of them; it's polite to use, but not overly so. If you'd like to sound extra polite, instead of
        <b>إنتا (ʔinta)</b> & <b>إنتي (ʔinti)</b> you can use <b>حضرتك (ħaḍratak/ek)</b> — similar to <b>"sir"</b> or
        <b>"ma'am"</b>. It's very polite to address strangers this way, but it's not mandatory.</p>

    <x-sentence-item eng="how are you, sir?">
        <x-sentence-term arb="كيف" eng="how" :term="$terms['kīf'] ?? null"/>
        <x-sentence-term arb="حضرتَك" eng="you sir" :term="$terms['ħaḍratak'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="where are you from, ma'am?">
        <x-sentence-term arb="من" eng="from" :term="$terms['min'] ?? null"/>
        <x-sentence-term arb="وين" eng="where" :term="$terms['wēn'] ?? null"/>
        <x-sentence-term arb="حضرتِك" eng="you ma'am" :term="$terms['ħaḍratak'] ?? null"/>
    </x-sentence-item>

    <p>We greet others with <b>يعطيك العافية (yaʕṭīk l-ʕāfye "may He give you well-being")</b> when we find them in work
        situations or after they have performed some type of service.</p>

    <x-sentence-item eng="hello, may He give you well-being">
        <x-sentence-term arb="مرحبا" eng="hello" :term="$terms['marħaba'] ?? null"/>
        <x-sentence-term arb="يعطيك العافية" eng="may He give you well-being" :term="$terms['yaʕṭīk l-ʕāfye'] ?? null"/>
    </x-sentence-item>

    <x-inflections
        conjM="حضرتَك" conjMtr="ħaḍrtak"
        conjF="حضرتِك" conjFtr="ħaḍrtik"
    ></x-inflections>

    <x-inflections
        conjM="أغلّبَك" conjMtr="ʔaġallbak"
        conjF="أغلّبِك" conjFtr="ʔaġallbik"
    ></x-inflections>

    <x-inflections
        conjM="ولا يهمَّك" conjMtr="wala yhimmak"
        conjF="ولا يهمِّك" conjFtr="wala yhimmik"
    ></x-inflections>

    <x-inflections
        conjM="يعطيك العافية" conjMtr="yaʕṭīk l-ʕāfye"
        conjF="يعطيكي العافية" conjFtr="yaʕṭīki l-ʕāfye"
    ></x-inflections>

    <x-inflections
        conjM="يسلمو إيديك" conjMtr="yislamu ʔīdēk"
        conjF="يسلمو إيديكي" conjFtr="yislamu ʔīdēki"
    ></x-inflections>
</x-lesson-concept>

<x-activity-area title="{{ __('dialogue') }}">
    <p>Shadow the following conversation between ? & ? in ?:</p>
    <div class="dialog-body">
        <x-dialog-line speaker="جريس" arb="يعطيكي العافية"/>
        <x-dialog-line speaker="لمى" arb="الله يعافيك"/>
        <x-dialog-line speaker="جريس" arb="أنا جاي اليوم عشان الدكتور فارس"/>
        <x-dialog-line speaker="لمى" arb="تمام، ممكن الإسم الكامل؟"/>
        <x-dialog-line speaker="جريس" arb="أكيد، الإسم جريس مؤمن وائل ريزق"/>
        <x-dialog-line speaker="لمى" arb="شكرا — تاريخ الميلاد، لو سمحت؟"/>
        <x-dialog-line speaker="جريس" arb="طبعًا، ٤/٦/١٩٨٣"/>
        <x-dialog-line speaker="لمى" arb="شكرا — ورقم التلفون؟"/>
        <x-dialog-line speaker="جريس" arb="٠٥٩٤٢٨٥١٠٣"/>
        <x-dialog-line speaker="لمى" arb="شكرا كتير — الدكتور جاهز، تفضّل"/>
    </div>
</x-activity-area>
