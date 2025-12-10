<x-vue.deck component="DeckContainer" :deck="\App\Models\Deck::find(58)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>We can compare things in Arabic using a form of the adjective known as the <b>elative form</b>, which is both <b>comparative</b>
        (i.e. <b>"more"</b>) & <b>superlative</b> (i.e. <b>"most"</b>) in meaning; its exact meaning is set by the
        syntax. We will start by learning how to use <b>elative adjectives</b> predicatively, just like any other
        adjective.</p>
    <x-sentence-item eng="this man is older than the other">
        <x-sentence-term arb="هالزلمة" eng="this-man" :term="$terms['zalame'] ?? null"/>
        <x-sentence-term arb="أكبر" eng="older" :term="$terms['kbīr'] ?? null"/>
        <x-sentence-term arb="من" eng="than" :term="$terms['min'] ?? null"/>
        <x-sentence-term arb="التاني" eng="the-other" :term="$terms['tāny'] ?? null"/>
    </x-sentence-item>
    <p>Regardless of the adjective's lemma form, the <b>elative form</b> is always in the <b>أفعل (ʔafʕal)</b> pattern &
        does not follow gender & number agreement; it has no feminine or plural form.</p>
    <x-sentence-item eng="this coffee is tastier than the other">
        <x-sentence-term arb="هالقهوة" eng="this-coffee" :term="$terms['ʔahwe'] ?? null"/>
        <x-sentence-term arb="أزكى" eng="tastier" :term="$terms['zāky'] ?? null"/>
        <x-sentence-term arb="من" eng="than" :term="$terms['min'] ?? null"/>
        <x-sentence-term arb="التانية" eng="the-other" :term="$terms['tāny'] ?? null"/>
    </x-sentence-item>

    <p>Not all adjectives have an <b>elative form</b>. In that case, we use <b>أكتر (ʔaktar "more")</b> after the
        adjective:</p>
    <x-sentence-item eng="this man is more famous than the other">
        <x-sentence-term arb="هالزلمة" eng="this-man" :term="$terms['zalame'] ?? null"/>
        <x-sentence-term arb="مشهور" eng="famous" :term="$terms['mašhūr'] ?? null"/>
        <x-sentence-term arb="أكتر" eng="more" :term="$terms['ktīr'] ?? null"/>
        <x-sentence-term arb="من" eng="than" :term="$terms['min'] ?? null"/>
        <x-sentence-term arb="التاني" eng="the-other" :term="$terms['tāny'] ?? null"/>
    </x-sentence-item>
    <p>In fact, <b>أكتر (ʔaktar "more")</b> can & does modify entire phrases as well as single adjectives:</p>
    <x-sentence-item eng="Paris is closer to London than Jerusalem is">
        <x-sentence-term arb="باريس" eng="Paris" :term="$terms['zalame'] ?? null"/>
        <x-sentence-term arb="قريبة" eng="near" :term="$terms['mašḡūl'] ?? null"/>
        <x-sentence-term arb="على" eng="to" :term="$terms['ktīr'] ?? null"/>
        <x-sentence-term arb="لندن" eng="London" :term="$terms['ktīr'] ?? null"/>
        <x-sentence-term arb="أكتر" eng="more" :term="$terms['tāny'] ?? null"/>
        <x-sentence-term arb="من" eng="than" :term="$terms['min'] ?? null"/>
        <x-sentence-term arb="القدس" eng="Jerusalem" :term="$terms['tāny'] ?? null"/>
    </x-sentence-item>
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

    <p>By default, all adjectives — in all their diverse forms — have <b>sound plurals</b>:</p>

    <x-inflections title="happy"
                   conjM="مبسوط" conjMtr="mabsūṭ"
                   conjF="مبسوطة" conjFtr="mabsūṭa"
                   conjP="مبسوطين" conjPtr="mabsūṭīn"
    ></x-inflections>

    <p>However, there is one type of adjective that may have a <b>broken plural</b> instead. Adjectives in the
        <b>فعيل (fʕīl)</b> pattern may have a plural in the <b>فعال (fʕāl)</b> pattern instead; the majority —
        but not all — do.</p>

    <x-inflections title="nice"
                   conjM="لطيف" conjMtr="laṭīf"
                   conjF="لطيفة" conjFtr="laṭīfe"
                   conjP="لطيفين" conjPtr="laṭīfīn"
    ></x-inflections>

    <x-inflections title="big"
                   conjM="كبير" conjMtr="kbīr"
                   conjF="كبيرة" conjFtr="kbīre"
                   conjP="كبار" conjPtr="kbār"
    ></x-inflections>

    <div class="activity-area">
        <div class="array">
            <div class="activity-info-container">
                <div>SINGULAR</div>
                <div>PLURAL</div>

                <div>منيح</div>
                <div>مناح</div>

                <div>كبير</div>
                <div>كبار</div>

                <div>زغير</div>
                <div>زغار</div>

                <div>جديد</div>
                <div>جداد</div>

                <div>قديم</div>
                <div>قدام</div>

                <div>ثقيل</div>
                <div>ثقال</div>

                <div>خفيف</div>
                <div>خفاف</div>

                <div>طويل</div>
                <div>طوال</div>

                <div>قصير</div>
                <div>قصار</div>

                <div>لطيف</div>
                <div>لطيفين</div>

                <div>غريب</div>
                <div>غريبين</div>
            </div>
        </div>
    </div>
</x-lesson-concept>

<x-activity-area title="{{ __('exercise') }}">
    <div class="question-group shuffle">
        <div class="activity-fill">
            <p>العربي أسهل من الصيني؟</p>
        </div>
        <div class="activity-fill">
            <p>الذهب أثقل من الفضى؟</p>
        </div>
        <div class="activity-fill">
            <p>المدرسة أقصر من الجامعة؟</p>
        </div>
        <div class="activity-fill">
            <p>باريس قريبة على لندن أكتر من القدس؟</p>
        </div>
    </div>

    <div class="question-group">
        <div class="activity-fill">
            <p>وين أبرد، بالقدس ولّا بيافا؟</p>
        </div>
        <div class="activity-fill">
            <p>وين أشوب، بالإمارات ولّا بلبنان؟</p>
        </div>
        <div class="activity-fill">
            <p>شو أزكى، الشاي ولّا القهوة؟</p>
        </div>
        <div class="activity-fill">
            <p>شو أحلى، الحياة بالمدينة ولّا الحياة بالقرية؟</p>
        </div>
        <div class="activity-fill">
            <p>مين مشغول أكتر، الطالب ولّا المعلّم؟</p>
        </div>
        <div class="activity-fill">
            <p>مين مبسوط أكتر، ولد بالمستشفى ولّا ولد بالمدرسة؟</p>
        </div>
    </div>

    <p>Create sentences that compare the following items using the adjectives provided:</p>
    <div class="array">
        <div class="activity-info-container">
            <div>الإنجليزيّ</div>
            <div>الإسبانيّ</div>
            <div>الفرنسيّ</div>
            <div>العربيّ</div>
            <div>الروسيّ</div>
            <div>الصينيّ</div>
        </div>
    </div>

    <div class="question-group">
        <div class="activity-fill">
            <p>________ أصعب من ________</p>
        </div>
        <div class="activity-fill">
            <p>________ أحلى من ________</p>
        </div>
        <div class="activity-fill">
            <p>________ مشهور أكثر من ________</p>
        </div>
        <div class="activity-fill">
            <p>________ قريب عالألمانيّ أكثر ________</p>
        </div>
    </div>
</x-activity-area>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">

</x-lesson-concept>
