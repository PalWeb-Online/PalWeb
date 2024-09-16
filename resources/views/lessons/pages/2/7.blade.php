<x-deck-container :deck="\App\Models\Deck::find(53)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>In addition to <b>شكله (šiklo "to seem")</b>, the only true <b>pseudo-verb</b> is <b>بدّه (biddo "to want")</b>.
        Since <b>بدّه (biddo)</b> does not come from any other word currently used in Palestinian Arabic, it's
        completely
        impossible for it to appear without a clitic pronoun.</p>

    <x-sentence-item eng="I want a glass of water">
        <x-sentence-term arb="بدّي" eng="1S.want" :term="$terms['biddo'] ?? null"/>
        <x-sentence-term arb="كاسة" eng="cup" :term="$terms['kāse'] ?? null"/>
        <x-sentence-term arb="ميّ" eng="water" :term="$terms['mayy'] ?? null"/>
    </x-sentence-item>

    <p>In terms of its meaning, it can either express desire or need (i.e. <b>"to need"</b>). Which of these meanings is
        intended simply depends on context.</p>

    <x-sentence-item eng="I need your phone number & address">
        <x-sentence-term arb="بدّي" eng="1S.need" :term="$terms['biddo'] ?? null"/>
        <x-sentence-term arb="رقم" eng="number" :term="$terms['raqam'] ?? null"/>
        <x-sentence-term arb="تلفونك" eng="your-phone" :term="$terms['talafōn'] ?? null"/>
        <x-sentence-term arb="و" eng="&" :term="$terms['w-'] ?? null"/>
        <x-sentence-term arb="عنوانك" eng="your-address" :term="$terms['ʕinwān'] ?? null"/>
    </x-sentence-item>
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

    <p>We use the preposition <b>لـ (la- "for")</b> to refer to the beneficiary or purpose of something. Be careful to
        note that it's the only clitic that deletes the vowel of the article <b>الـ</b> in writing:</p>
    <x-sentence-item eng="I need a gift for my friend">
        <x-sentence-term arb="بدّي" eng="1S.need" :term="$terms['biddo'] ?? null"/>
        <x-sentence-term arb="هديّة" eng="gift" :term="$terms['hadiyye'] ?? null"/>
        <x-sentence-term arb="لـ" eng="for" :term="$terms['la-'] ?? null"/>
        <x-sentence-term arb="ـصاحبي" eng="my-friend" :term="$terms['ṣāħib'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="food for the cats">
        <x-sentence-term arb="أكل" eng="food" :term="$terms['ʔakl'] ?? null"/>
        <x-sentence-term arb="لـ" eng="for" :term="$terms['la-'] ?? null"/>
        <x-sentence-term arb="ـلبسس" eng="the-cats" :term="$terms['bisse'] ?? null"/>
    </x-sentence-item>
</x-lesson-concept>

<x-activity-area title="{{ __('exercise') }}">
    <p>Consider the following information about your classmates' birthdays & use it to complete the following
        exercises.</p>
    <div class="array">
        <div class="activity-info-container" style="grid-template-columns: repeat(2, 1fr);">
            <div>الطالب</div>
            <div>عيد الميلاد</div>

            <div>أكرم</div>
            <div>3/7</div>

            <div>غسّان</div>
            <div>15/3</div>

            <div>شادي</div>
            <div>11/9</div>

            <div>جريس</div>
            <div>12/2</div>

            <div>يارا</div>
            <div>27/10</div>

            <div>دانا</div>
            <div>19/1</div>

            <div>سما</div>
            <div>30/5</div>

            <div>ميرا</div>
            <div>18/11</div>
        </div>
    </div>

    <p>Your loved one's birthday is coming up & you're trying to find a good gift: something thoughtful that isn't
        too expensive. Consider the following gift ideas & use the information to complete the following
        exercises.</p>
    <div class="array">
        <div class="activity-info-container" style="grid-template-columns: repeat(2, 1fr);">
            <div>الهديّة</div>
            <div>السعر</div>

            <div>كتاب</div>
            <div>$16</div>

            <div>صورة إلكم</div>
            <div>$4</div>

            <div>بسكليت</div>
            <div>$200</div>

            <div>علبة شاي</div>
            <div>$8</div>

            <div>حفلة</div>
            <div>$150</div>

            <div>ساعة</div>
            <div>$50</div>

            <div>تلفون</div>
            <div>$300</div>

            <div>بلوزة</div>
            <div>$25</div>
        </div>
    </div>

    <p>{{ __('activity.complete-sentence') }}</p>
    <div class="activity-dialog">
        <x-dialog-line speaker="سؤال" arb="الكتاب بقدّيش؟" eng="(for) how much is the book?"/>
        <x-dialog-line speaker="جواب" arb="الكتاب بستّعش" eng="the book is (for) sixteen"/>
    </div>

    <div class="question-group shuffle">
        <x-activity-fill
            que="الشاي بقدّيش؟"
            ans="الشاي بثمنية"/>
        <x-activity-fill
            que="البلوزة بقدّيش؟"
            ans="البلوزة بخمسة وعشرين"/>
        <x-activity-fill
            que="الساعة بقدّيش؟"
            ans="الساعة بخمسين"/>
        <x-activity-fill
            que="البسكليت بقدّيش؟"
            ans="البسكليت بميّتين"/>
    </div>

    <p>{{ __('activity.fill-in') }}</p>
    <div class="question-group shuffle">
        <x-activity-mc que="بدّي إشي لطيف ورخيص — مثلًا زي" ans="a"
                       a="صورة"
                       b="ساعة"
        />
        <x-activity-mc que="بدّي إشي كبير وغالي — مثلًا زي" ans="a"
                       a="حفلة"
                       b="شاي"
        />
        <x-activity-mc que="بدّي إشي رخيص بس مفيد — مثلًا زي" ans="b"
                       a="تلفون"
                       b="كتاب"
        />
        <x-activity-mc que="بدّي إشي غالي بس مفيد — مثلًا زي" ans="b"
                       a="بلوزة"
                       b="بسكليت"
        />
    </div>

</x-activity-area>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">
    <p>Let's talk about one of the most important adverbs in Palestinian Arabic: <b>هيك (hēk "like so")</b>.</p>
</x-lesson-concept>
