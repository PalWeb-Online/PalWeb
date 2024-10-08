<x-deck-container :deck="\App\Models\Deck::find(30)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">
    <p>Like most languages, Arabic distinguishes between <b>definite</b> & <b>indefinite</b> nouns. English uses
        the articles <b>"the"</b> & <b>"a(n)"</b> to distinguish <b>definite</b> & <b>indefinite</b> nouns,
        respectively. In Arabic, nouns are <b>indefinite</b> by default; we simply add the article <b>الـ (-l)</b> to
        make a
        noun <b>definite</b>. Note that <b>الـ (-l)</b> is a <b>clitic</b>, meaning it's always attached to something;
        it can never be written as a standalone
        word.</p>

    <div class="array">
        <x-sentence-item eng="the house">
            <x-sentence-term arb="الـ" eng="the"/>
            <x-sentence-term arb="ـبيت" eng="house"/>
        </x-sentence-item>
        <x-sentence-item eng="a house">
            <x-sentence-term arb="بيت" eng="house"/>
        </x-sentence-item>
    </div>

    <p>Of course, <b>proper nouns</b> — like names, places, etc. — are already definite, so they don't need the article.
        But while English usually doesn't use <b>"the"</b> when referring to general concepts, Arabic does.</p>

    <div class="array">
        <x-sentence-item eng="Arabic">
            <x-sentence-term arb="الـ" eng="the" :term="$terms['l-'] ?? null"/>
            <x-sentence-term arb="ـعربيّ" eng="Arabic" :term="$terms['ʕarabi'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="Palestine">
            <x-sentence-term arb="فلسطين" eng="Palestine" :term="$terms['falasṭīn'] ?? null"/>
        </x-sentence-item>
    </div>

    <p>Some consonants cause <b>الـ (-l)</b> to merge into them, doubling the sound; these
        are traditionally called <b>sun letters</b>, while ordinary consonants are <b>moon letters</b>. (Phonetically,
        <b>sun letters</b> are consonants pronounced with the tip of the
        tongue; the merger eases pronunciation, since the consonant <b>/l/</b> is articulated the same way but in a
        different location.) Note that this feature has no effect on spelling.</p>

    <div class="array">
        <x-sentence-item eng="the moon">
            <x-sentence-term arb="القمر" eng="l-ʔamar" :term="$terms['ʔamar'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="the sun">
            <x-sentence-term arb="الشمس" eng="š-šams" :term="$terms['šams'] ?? null"/>
        </x-sentence-item>
    </div>

    <x-collapsible title="SEE MORE">
        <p>Pay attention to the following words. Do you notice anything strange about them?</p>
        <div class="array" style="margin: 0 0 1.6rem">
            <x-sentence-item eng="the chair">
                <x-sentence-term arb="الكرسي" eng="il-kursi" :term="$terms['kursi'] ?? null"/>
            </x-sentence-item>
            <x-sentence-item eng="the book">
                <x-sentence-term arb="الكتاب" eng="li-ktāb" :term="$terms['ktāb'] ?? null"/>
            </x-sentence-item>
        </div>
        <p>In one, the article sounds like <b>[ɪl]</b> or just <b>[l]</b> — but it sounds like <b>[lɪ]</b> in the other!
        </p>
        <p>You may hear this & think the article has two different-sounding forms, but this is not the case. Despite its
            somewhat misleading spelling, the article itself is just a consonant: <b>/l/</b>. As a result, when this
            consonant is attached to a word that already starts with multiple consonants, an extra vowel is inserted
            between them to make things easier to pronounce, resulting in <b>[lɪ]</b>.</p>
        <p>Known as an <b>epenthetic vowel</b>, this vowel is simply added to make pronunciation easier & is used all
            throughout Palestinian Arabic.</p>
        <p>Note that this separation causes the <b>sun letters</b> to lose their effect.</p>
        <div class="array" style="margin: 0 0 1.6rem">
            <x-sentence-item eng="the olive">
                <x-sentence-term arb="الزتون" eng="iz-zatūn" :term="$terms['zatūn'] ?? null"/>
            </x-sentence-item>
            <x-sentence-item eng="the garbage">
                <x-sentence-term arb="الزبالة" eng="li-zbāle" :term="$terms['zbāle'] ?? null"/>
            </x-sentence-item>
        </div>
    </x-collapsible>
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">
    <p>Using prepositions is easy in Arabic, especially given that we don't use <b>"to be"</b> in the <b>Present
            Tense</b>. In this section, we will start using one of the most important
        prepositions in Arabic: <b>بـ (b- "in")</b>. Notice that <b>بـ (b-)</b> is a <b>clitic</b> as well. We can use
        <b>بـ (b-)</b>, first of all, to indicate the location of something.</p>

    <x-sentence-item eng="I am in Jerusalem">
        <x-sentence-term arb="أنا" eng="I" :term="$terms['ʔana'] ?? null"/>
        <x-sentence-term arb="بـ" eng="in" :term="$terms['b-'] ?? null"/>
        <x-sentence-term arb="القدس" eng="Jerusalem" :term="$terms['l-ʔuds'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="Jerusalem is in Palestine">
        <x-sentence-term arb="القدس" eng="Jerusalem" :term="$terms['l-ʔuds'] ?? null"/>
        <x-sentence-term arb="بـ" eng="in" :term="$terms['b-'] ?? null"/>
        <x-sentence-term arb="ـفلسطين" eng="Palestine" :term="$terms['falasṭīn'] ?? null"/>
    </x-sentence-item>

    <p>But we can use this term in an abstract sense as well, as we do in English. We'll be using this word a lot to ask
        for the meanings of other Arabic terms, so don't forget it!</p>

    <x-sentence-item eng="what's 'l-ʔuds' in English?" wrapped>
        <x-sentence-term arb="شو" eng="what" :term="$terms['šu'] ?? null"/>
        <x-sentence-term arb="القدس" eng="'l-ʔuds'" :term="$terms['l-ʔuds'] ?? null"/>
        <x-sentence-term arb="بـ" eng="in" :term="$terms['b-'] ?? null"/>
        <x-sentence-term arb="ـالإنجليزي" eng="the-English" :term="$terms['ʔinglīzi'] ?? null"/>
    </x-sentence-item>
</x-lesson-concept>


<x-activity-area title="{{ __('exercise') }}">
    <p>{{ __('activity.multiple-choice') }}</p>
    <div class="question-group shuffle">
        <x-activity-mc que="شو دمشق بالإنجليزي؟" ans="a"
                       a="Damascus"
                       b="Beirut"
                       c="Amman"
                       d="Cairo"
        />
        <x-activity-mc que="شو بيروت بالإنجليزي؟" ans="b"
                       a="Damascus"
                       b="Beirut"
                       c="Amman"
                       d="Cairo"
        />
        <x-activity-mc que="شو عمّان بالإنجليزي؟" ans="c"
                       a="Damascus"
                       b="Beirut"
                       c="Amman"
                       d="Cairo"
        />
        <x-activity-mc que="شو القاهرة بالإنجليزي؟" ans="d"
                       a="Damascus"
                       b="Beirut"
                       c="Amman"
                       d="Cairo"
        />
    </div>
    <p>{{ __('activity.fill-in') }}</p>
    <div class="question-group shuffle">
        <x-activity-fill
            que="القدس بالإنجليزي ..."
            ans="Jerusalem"/>
        <x-activity-fill
            que="الخليل بالإنجليزي ..."
            ans="Hebron"/>
        <x-activity-fill
            que="ريحا بالإنجليزي ..."
            ans="Jericho"/>
        <x-activity-fill
            que="غزّة بالإنجليزي ..."
            ans="Gaza"/>
    </div>

    <p>Consider the following information about cities around the Levant & use it to complete the following
        exercises.</p>
    <div class="array" style="align-items: flex-start">
        <div class="activity-info-container">
            <div>المدينة</div>
            <div>البلد</div>

            <div>القدس</div>
            <div>فلسطين</div>

            <div>الخليل</div>
            <div>فلسطين</div>

            <div>الناصرة</div>
            <div>فلسطين</div>

            <div>يافا</div>
            <div>فلسطين</div>

            <div>غزّة</div>
            <div>فلسطين</div>

            <div>ريحا</div>
            <div>فلسطين</div>

            <div>بيت لحم</div>
            <div>فلسطين</div>
        </div>
        <div class="activity-info-container">
            <div>المدينة</div>
            <div>البلد</div>

            <div>دمشق</div>
            <div>سوريا</div>

            <div>حلب</div>
            <div>سوريا</div>

            <div>بيروت</div>
            <div>لبنان</div>

            <div>عمّان</div>
            <div>الأردن</div>

            <div>القاهرة</div>
            <div>مصر</div>
        </div>
    </div>

    <p>{{ __('activity.yes-no') }}</p>
    <div class="question-group shuffle">
        <x-activity-mc que="القدس بفلسطين؟" ans="a"
                       a="آه"
                       b="لأ"
        />
        <x-activity-mc que="الخليل بالأردن؟" ans="b"
                       a="آه"
                       b="لأ"
        />
        <x-activity-mc que="غزّة بفلسطين؟" ans="a"
                       a="آه"
                       b="لأ"
        />
        <x-activity-mc que="بيت لحم بلبنان؟" ans="b"
                       a="آه"
                       b="لأ"
        />
        <x-activity-mc que="حلب بسوريا؟" ans="a"
                       a="آه"
                       b="لأ"
        />
        <x-activity-mc que="عمّان بمصر؟" ans="b"
                       a="آه"
                       b="لأ"
        />
    </div>

    <p>{{ __('activity.complete-sentence') }}</p>
    <div class="question-group shuffle">
        <x-activity-fill
            que="الناصرة وين؟"
            ans="الناصرة بفلسطين"/>
        <x-activity-fill
            que="وين يافا؟"
            ans="يافا بفلسطين"/>
        <x-activity-fill
            que="ريحا وين؟"
            ans="ريحا بفلسطين"/>
        <x-activity-fill
            que="وين دمشق؟"
            ans="دمشق بسوريا"/>
        <x-activity-fill
            que="بيروت وين؟"
            ans="بيروت بلبنان"/>
        <x-activity-fill
            que="وين القاهرة؟"
            ans="القاهرة بمصر"/>
    </div>
</x-activity-area>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">
    <p>We can sound a lot more natural in Arabic just by sprinkling in a few common words. In addition to the filler
        word <b>يعني (yaʕni)</b>, we can use <b>يلّا (yalla "let's go")</b> either literally or to casually agree to
        an
        idea or even gently end small-talk & move on.</p>
    <x-sentence-item eng="shall we get going?">
        <x-sentence-term arb="يلّا؟" eng="let's go" :term="$terms['yalla'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="come on, let's go!">
        <x-sentence-term arb="يلّا" eng="let's go" :term="$terms['yalla'] ?? null"/>
        <x-sentence-term arb="يلّا" eng="let's go" :term="$terms['yalla'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="alright then, bye">
        <x-sentence-term arb="يلّا" eng="let's go" :term="$terms['yalla'] ?? null"/>
        <x-sentence-term arb="سلامات" eng="goodbye" :term="$terms['salāmāt'] ?? null"/>
    </x-sentence-item>

    <x-collapsible title="SEE MORE">
        <p>How do you repond to <b>صباح الخير (ṣabāħ l-xēr)</b> & <b>مسا الخير (masa l-xēr)</b>? While you can reply
            with the same phrase, it's customary in Arabic to reply with something slightly different. In this case,
            it's common to substitute <b>خير (xēr "good tidings")</b> with another noun that conveys positivity.
            While the most common responses are <b>صباح النور (ṣabāħ n-nūr)</b> & <b>مسا النور (masa n-nūr)</b>,
            it's also very common to use the names of different types of flowers!</p>

        <div class="array">
            <x-sentence-item eng="good morning">
                <x-sentence-term arb="صباح النور" eng="ṣabāħ n-nūr" :term="$terms['ṣabāħ l-xēr'] ?? null"/>
            </x-sentence-item>
            <x-sentence-item eng="good morning">
                <x-sentence-term arb="صباح الحبّ" eng="ṣabāħ l-ħubb" :term="$terms['ṣabāħ l-xēr'] ?? null"/>
            </x-sentence-item>
            <x-sentence-item eng="good morning">
                <x-sentence-term arb="صباح الورد" eng="ṣabāħ l-ward" :term="$terms['ṣabāħ l-xēr'] ?? null"/>
            </x-sentence-item>
            <x-sentence-item eng="good morning">
                <x-sentence-term arb="صباح الفلّ" eng="ṣabāħ l-full" :term="$terms['ṣabāħ l-xēr'] ?? null"/>
            </x-sentence-item>
            <x-sentence-item eng="good morning">
                <x-sentence-term arb="صباح اليسمين" eng="ṣabāħ l-yasmīn" :term="$terms['ṣabāħ l-xēr'] ?? null"/>
            </x-sentence-item>
        </div>
    </x-collapsible>
</x-lesson-concept>

<x-activity-area title="{{ __('dialogue') }}">
    <p>Shadow the following conversation. Jeries & Shadi were standing together when a third person passes
        by:</p>

    <div class="activity-dialog">
        <x-dialog-line speaker="جريس" arb="صباح الخير، أكرم"/>
        <x-dialog-line speaker="أكرم" arb="جريس؟ أهلين، صباح النور"/>
        <x-dialog-line speaker="جريس" arb="أكرم، شادي — شادي، أكرم"/>
        <x-dialog-line speaker="شادي" arb="مرحبا، أنا شادي — تشرّفنا"/>
        <x-dialog-line speaker="أكرم" arb="إلي الشرف، من وين إنتا؟"/>
        <x-dialog-line speaker="شادي" arb="من يافا، وإنتا؟"/>
        <x-dialog-line speaker="أكرم" arb="أهلا وسهلا، أنا من ريحا"/>
        <x-dialog-line speaker="شادي" arb="أهلين"/>
        <x-dialog-line speaker="جريس" arb="يلّا، شادي؟"/>
        <x-dialog-line speaker="شادي" arb="يلّا"/>
        <x-dialog-line speaker="جريس" arb="سلامات، أكرم"/>
        <x-dialog-line speaker="أكرم" arb="يلّا، سلامات"/>
    </div>

    <x-activity-mc que="مين من ريحا؟" ans="c" shuffle
                   a="جريس"
                   b="شادي"
                   c="أكرم"
    />
    <x-activity-mc que="شادي من وين؟" ans="b" shuffle
                   a="الخليل"
                   b="يافا"
                   c="ريحا"
                   d="غزّة"
    />

    <p>Use the template to ask for the meanings of the following terms in English or Arabic:</p>

    <div class="array">
        <div class="activity-info-container" style="grid-template-columns: repeat(4, 1fr);">
            <div>بلد</div>
            <div>مدينة</div>
            <div>فلسطين</div>
            <div>القدس</div>
        </div>
    </div>

    <div class="activity-dialog">
        <x-dialog-line speaker="جريس" arb="شادي؟"/>
        <x-dialog-line speaker="شادي" arb="آه؟ شو؟"/>
        <x-dialog-line speaker="جريس" arb="شو ________ بالـ________؟"/>
        <x-dialog-line speaker="شادي" arb="________؟ ________ بالـ________ ________"/>
        <x-dialog-line speaker="جريس" arb="شكرًا"/>
        <x-dialog-line speaker="شادي" arb="عفوًا"/>
    </div>
</x-activity-area>
