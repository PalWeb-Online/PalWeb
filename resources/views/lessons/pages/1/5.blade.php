<x-deck-container :deck="\App\Models\Deck::find(42)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>In Arabic, we express the meaning of <b>"there is"</b> by inserting <b>فيه (fīh)</b> at the start of the
        sentence; this type of sentence is called an <b>existential sentence</b>.</p>
    <x-sentence-item eng="there is trash in the bag">
        <x-sentence-term arb="فيه" eng="(there is)" :term="$terms['fīh'] ?? null"/>
        <x-sentence-term arb="زبالة" eng="trash" :term="$terms['zbāle'] ?? null"/>
        <x-sentence-term arb="بـ" eng="in" :term="$terms['b-'] ?? null"/>
        <x-sentence-term arb="ـالكيس" eng="the-bag" :term="$terms['kīs'] ?? null"/>
    </x-sentence-item>
    <p>Note that <b>فيه (fīh)</b> itself is not a verb. Rather, it's a particle that is etymologically a preposition —
        analogous to the <b>"there"</b> in <b>"there is"</b>. Indeed, <b>existential sentences</b> are closely related
        to <b>nominal sentences</b> in that the verbal element of both (i.e. the verb <b>"to be"</b>) is implicit.
        Because of this, <b>فيه (fīh)</b> itself is frozen as-is & is never conjugated.</p>
    <div class="array">
        <x-sentence-item eng="there is a girl">
            <x-sentence-term arb="فيه" eng="in-it" :term="$terms['fīh'] ?? null"/>
            <x-sentence-term arb="بنت" eng="girl" :term="$terms['bint'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="there is a boy">
            <x-sentence-term arb="فيه" eng="in-it" :term="$terms['fīh'] ?? null"/>
            <x-sentence-term arb="ولد" eng="boy" :term="$terms['walad'] ?? null"/>
        </x-sentence-item>
    </div>
    <x-sentence-item eng="there are children">
        <x-sentence-term arb="فيه" eng="in-it" :term="$terms['fīh'] ?? null"/>
        <x-sentence-term arb="ولاد" eng="children" :term="$terms['walad'] ?? null"/>
    </x-sentence-item>

    <p>We negate <b>فيه (fīh)</b> with a word very similar to <b>مش (miš)</b> — <b>فش (fiš)</b>:</p>
    <x-sentence-item eng="there is no trash in the bag">
        <x-sentence-term arb="فش" eng="there isn't" :term="$terms['fīh'] ?? null"/>
        <x-sentence-term arb="زبالة" eng="trash" :term="$terms['zbāle'] ?? null"/>
        <x-sentence-term arb="بـ" eng="in" :term="$terms['b-'] ?? null"/>
        <x-sentence-term arb="ـالكيس" eng="the-bag" :term="$terms['kīs'] ?? null"/>
    </x-sentence-item>
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

    <p>It's time to learn another preposition used to indicate the location of something: <b>على (ʕala "on")</b> — often
        shortened to just <b>عـ (ʕa-)</b>.</p>
    <x-sentence-item eng="the cup is on the table">
        <x-sentence-term arb="الكاسة" eng="the-cup" :term="$terms['kāse'] ?? null"/>
        <x-sentence-term arb="عـ" eng="on" :term="$terms['ʕala'] ?? null"/>
        <x-sentence-term arb="ـالطاولة" eng="the-table" :term="$terms['ṭāwla'] ?? null"/>
    </x-sentence-item>

    <p>Notice the difference between <b>بـ (b-)</b> & <b>عـ (ʕa-)</b>. Since the former specifically means
        <b>"inside"</b>, they are not interchangeable; that is, usually <b>بـ (b-)</b> refers to a container, while
        <b>عـ (ʕa-)</b> refers to a surface.</p>
    <x-sentence-item eng="the coffee is in the cup">
        <x-sentence-term arb="القهوة" eng="the-coffee" :term="$terms['ʔahwe'] ?? null"/>
        <x-sentence-term arb="بـ" eng="in" :term="$terms['b-'] ?? null"/>
        <x-sentence-term arb="ـالكاسة" eng="the-cup" :term="$terms['kāse'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="the coffee is on the shirt">
        <x-sentence-term arb="القهوة" eng="the-coffee" :term="$terms['ʔahwe'] ?? null"/>
        <x-sentence-term arb="عـ" eng="on" :term="$terms['ʕala'] ?? null"/>
        <x-sentence-term arb="ـالبلوزة" eng="the-shirt" :term="$terms['blūze'] ?? null"/>
    </x-sentence-item>

    <p>In fact, Arabic is very literal in its use of <b>بـ (b-)</b> & <b>عـ (ʕa-)</b>, so avoid translating directly —
        think literally!</p>
    <x-sentence-item eng="he is at home">
        <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
        <x-sentence-term arb="بـ" eng="in" :term="$terms['b-'] ?? null"/>
        <x-sentence-term arb="ـالبيت" eng="the-house" :term="$terms['bēt'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="he is in bed">
        <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
        <x-sentence-term arb="عـ" eng="on" :term="$terms['ʕala'] ?? null"/>
        <x-sentence-term arb="ـالتخت" eng="the-bed" :term="$terms['taxt'] ?? null"/>
    </x-sentence-item>
</x-lesson-concept>

<x-activity-area title="{{ __('exercise') }}">
    <p>Choose how you would react to learning the following information.</p>
    <div class="question-group shuffle">
        <x-activity-mc que="الميّ بالكاسة" ans="a"
                       a="تمام"
                       b="يي"
        />
        <x-activity-mc que="القهوة عالبلوزة" ans="b"
                       a="تمام"
                       b="يي"
        />
        <x-activity-mc que="الولاد بالمدرسة" ans="a"
                       a="تمام"
                       b="يي"
        />
        <x-activity-mc que="الشاي عالأرض" ans="b"
                       a="تمام"
                       b="يي"
        />
        <x-activity-mc que="الطاولة بالصالون" ans="a"
                       a="تمام"
                       b="يي"
        />
        <x-activity-mc que="الزبالة عالتخت" ans="b"
                       a="تمام"
                       b="يي"
        />
    </div>

    <p>Consider the following information about the location of various things around the house & use it to complete
        the
        following exercises.</p>
    <div class="array">
        <div class="activity-info-container" style="grid-template-columns: repeat(3, 1fr);">
            <div>THING</div>
            <div>PLACE</div>
            <div>ROOM</div>

            <div>ميّ</div>
            <div>أرض</div>
            <div>حمّام</div>

            <div>شاي</div>
            <div>كاسة</div>
            <div></div>

            <div>كيس</div>
            <div></div>
            <div>مطبخ</div>

            <div>كاسة</div>
            <div>طاولة</div>
            <div></div>

            <div>قهوة</div>
            <div>بلوزة</div>
            <div></div>

            <div>طاولة</div>
            <div></div>
            <div>صالون</div>

            <div>تخت</div>
            <div></div>
            <div>غرفة</div>

            <div>زبالة</div>
            <div>كيس</div>
            <div></div>

            <div>بلوزة</div>
            <div>تخت</div>
            <div></div>
        </div>
    </div>

    <p>{{ __('activity.fill-in') }}</p>
    <div class="question-group shuffle">
        <x-activity-mc que="الشاي عـ" ans="c"
                       a="الصالون"
                       b="الكاسة"
                       c="الطاولة"
        />
        <x-activity-mc que="الشاي بـ" ans="b"
                       a="المطبخ"
                       b="الكاسة"
                       c="الطاولة"
        />
        <x-activity-mc que="البلوزة عـ" ans="b"
                       a="الغرفة"
                       b="التخت"
                       c="الأرض"
        />
        <x-activity-mc que="البلوزة بـ" ans="a"
                       a="الغرفة"
                       b="التخت"
                       c="القهوة"
        />
        <x-activity-mc que="الميّ عـ" ans="c"
                       a="الحمّام"
                       b="الطاولة"
                       c="الأرض"
        />
        <x-activity-mc que="الميّ بـ" ans="a"
                       a="الحمّام"
                       b="الكاسة"
                       c="الأرض"
        />
    </div>

    <p>{{ __('activity.complete-sentence') }} Indicate the surface the object is on.</p>
    <div class="activity-dialog">
        <x-dialog-line speaker="سؤال" arb="وين الميّ؟" eng="where is the water?"/>
        <x-dialog-line speaker="جواب" arb="الميّ عالأرض" eng="the water is on the floor"/>
    </div>
    <div class="question-group shuffle">
        <x-activity-fill que="وين الشاي؟"
                         ans="الشاي عالطاولة"/>
        <x-activity-fill que="وين القهوة؟"
                         ans="القهوة عالبلوزة"/>
        <x-activity-fill que="وين البلوزة؟"
                         ans="البلوزة عالتخت"/>
    </div>

    <p>{{ __('activity.complete-sentence') }} Indicate the room the object is in.</p>
    <div class="activity-dialog">
        <x-dialog-line speaker="سؤال" arb="وين الميّ؟" eng="where is the water?"/>
        <x-dialog-line speaker="جواب" arb="الميّ بالحمّام" eng="the water is in the bathroom"/>
    </div>
    <div class="question-group shuffle">
        <x-activity-fill que="وين الشاي؟"
                         ans="الشاي بالصالون"/>
        <x-activity-fill que="وين البلوزة؟"
                         ans="البلوزة بالغرفة"/>
        <x-activity-fill que="وين الزبالة؟"
                         ans="الزبالة بالمطبخ"/>
    </div>
</x-activity-area>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">

    <p>We can form perfectly polite requests simply by using <b>ممكن (mumkin "possible")</b> — similar to <b>"may"</b>
        in English. Context fills in the rest.</p>
    <x-sentence-item eng="may (I ask) a question?">
        <x-sentence-term arb="ممكن" eng="possible" :term="$terms['mumkin'] ?? null"/>
        <x-sentence-term arb="سؤال؟" eng="question" :term="$terms['suʔāl'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="may (I have) some coffee?">
        <x-sentence-term arb="ممكن" eng="possible" :term="$terms['mumkin'] ?? null"/>
        <x-sentence-term arb="قهوة؟" eng="coffee.F" :term="$terms['ʔahwe'] ?? null"/>
    </x-sentence-item>

    <p>But being more polite than this — like saying <b>"please"</b> — is slightly more involved, because of the
        distinction between the masculine & feminine <b>"you"</b>. Not only do different pronouns exist for each, but
        slightly different verbal forms exist too. Since many common phrases are built
        around what are technically verbs, they may differ slightly based on the gender
        of the addressee. Namely, feminine forms are created by adding <b>ي (-i)</b>.</p>
    <x-sentence-item eng="may (I have) some coffee, please?">
        <x-sentence-term arb="ممكن" eng="possible" :term="$terms['mumkin'] ?? null"/>
        <x-sentence-term arb="قهوة" eng="coffee.F" :term="$terms['ʔahwe'] ?? null"/>
        <x-sentence-term arb="لو سمحت؟" eng="please (m.)" :term="$terms['law samaħt'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="may (I have) some coffee, please?">
        <x-sentence-term arb="ممكن" eng="possible" :term="$terms['mumkin'] ?? null"/>
        <x-sentence-term arb="قهوة" eng="coffee.F" :term="$terms['ʔahwe'] ?? null"/>
        <x-sentence-term arb="لو سمحتي؟" eng="please (f.)" :term="$terms['law samaħt'] ?? null"/>
    </x-sentence-item>
    <p>Let's use the gendered forms of two essential phrases to practice hearing these differences now.</p>

    <x-inflections
        conjM="لو سمحت" conjMtr="law samaħt"
        conjF="لو سمحتي" conjFtr="law samaħti"
    ></x-inflections>

    <x-inflections
        conjM="تفضّل" conjMtr="tfaḍḍal"
        conjF="تفضّلي" conjFtr="tfaḍḍali"
    ></x-inflections>
</x-lesson-concept>

<x-activity-area title="{{ __('dialogue') }}">
    <p>Shadow the following conversation, paying attention to the gender of each speaker.</p>
    <div class="activity-dialog">
        <x-dialog-line speaker="نور" arb="أهلا وسهلا بمطعم (بلدنا) — تفضّل"/>
        <x-dialog-line speaker="شمس" arb="أهلين — هون تمام؟"/>
        <x-dialog-line speaker="نور" arb="آه، تفضّل — هاذا المنيو"/>
        <x-dialog-line speaker="شمس" arb="شكرًا ... ممكن قهوة، لو سمحتي؟"/>
        <x-dialog-line speaker="نور" arb="تمام"/>
        <x-dialog-line speaker="شمس" arb="ووين الحمّام؟"/>
        <x-dialog-line speaker="نور" arb="الحمّام هناك"/>
        <x-dialog-line speaker="شمس" arb="شكرًا"/>
        <x-dialog-line speaker="نور" arb="عفوًا"/>
    </div>

    <div class="question-group">
        <x-activity-mc que="شمس زلمة ولّا مرة؟" ans="a"
                       a="زلمة"
                       b="مرة"
        />
        <x-activity-mc que="نور زلمة ولّا مرة؟" ans="b"
                       a="زلمة"
                       b="مرة"
        />
    </div>

    <p>Use the template to order the following items at a restaurant:</p>
    <div class="array">
        <div class="activity-info-container" style="grid-template-columns: repeat(3, 1fr);">
            <div>قهوة</div>
            <div>شاي</div>
            <div>ميّ</div>
        </div>
    </div>

    <div class="activity-dialog">
        <x-dialog-line speaker="نور" arb="أهلا وسهلا — تفضّل"/>
        <x-dialog-line speaker="شمس" arb="ممكن ________، لو سمحتي؟"/>
        <x-dialog-line speaker="نور" arb="آه، تمام"/>
        <x-dialog-line speaker="شمس" arb="شكرًا"/>
        <x-dialog-line speaker="نور" arb="عفوًا"/>
    </div>
</x-activity-area>
