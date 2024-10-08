<x-deck-container :deck="\App\Models\Deck::find(64)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>Normally, we think of any quantity that is greater than one to be grammatically plural. On a fundamental level,
        this is true in Arabic as well.</p>

    <x-sentence-item eng="a nice house">
        <x-sentence-term arb="بيت" eng="house" :term="$terms['bēt'] ?? null"/>
        <x-sentence-term arb="حلو" eng="(m.) nice" :term="$terms['ħilu'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="two nice houses">
        <x-sentence-term arb="بيتين" eng="two-houses" :term="$terms['bēt'] ?? null"/>
        <x-sentence-term arb="حلوين" eng="(p.) nice" :term="$terms['ħilu'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="three nice houses">
        <x-sentence-term arb="تلات" eng="three" :term="$terms['talāt'] ?? null"/>
        <x-sentence-term arb="بيوت" eng="houses" :term="$terms['bēt'] ?? null"/>
        <x-sentence-term arb="حلوين" eng="(p.) nice" :term="$terms['ħilu'] ?? null"/>
    </x-sentence-item>

    <p>However, undefined quantities of inanimate nouns are treated as single masses in Arabic; these masses are <b>grammatically
            feminine</b>. (In practice, even counted quantities may be <i>either plural or feminine</i>; the higher the
        number, the greater the preference for feminine agreement. While dual nouns are <i>always plural</i>, quantities
        greater than two may go either way.)</p>
    <x-sentence-item eng="nice houses">
        <x-sentence-term arb="بيوت" eng="houses" :term="$terms['bēt'] ?? null"/>
        <x-sentence-term arb="حلوة" eng="(f.) nice" :term="$terms['ħilu'] ?? null"/>
    </x-sentence-item>
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

    <p>We've learned that the vast majority of adjectives have <b>sound plurals</b>, with the exception of adjectives in
        the <b>فعيل</b> pattern. Just one other adjective pattern exists that exhibits irregular behavior; this is the
        so-called <b>Defect Adjective</b> pattern. What's especially unique about this pattern is that it not only has
        its own <b>broken plural</b>, but its feminine form is irregular as well.</p>

    <x-inflections title="red"
                   conjM="أحمر" conjMtr="ʔaħmar"
                   conjF="حمرا" conjFtr="ħamra"
                   conjP="حمر" conjPtr="ħumur"
    ></x-inflections>

    <p>As is often the case in Arabic, the pattern of these terms is connected to their meaning. <b>Defect
            Adjectives</b> usually refer to physical qualities (e.g. <b>أحمر "red"</b>) & abilities (e.g. <b>أهبل
            "dumb"</b>).</p>
</x-lesson-concept>

<x-activity-area title="{{ __('exercise') }}">
    <p>Match the request with the correct image:</p>
    <div class="question-group">
        <div class="activity-image" style="grid-template-columns: repeat(4, 1fr)">
            <div class="image-card">
                <img src="{{ asset('img/images/طاقيّة سودا.png') }}">
                <div>A</div>
            </div>
            <div class="image-card">
                <img src="{{ asset('img/images/بنطلون أخضر.png') }}">
                <div>B</div>
            </div>
            <div class="image-card">
                <img src="{{ asset('img/images/بلوزة حمرا.png') }}">
                <div>C</div>
            </div>
            <div class="image-card">
                <img src="{{ asset('img/images/قميص أبيض.png') }}">
                <div>D</div>
            </div>
            <div class="image-card">
                <img src="{{ asset('img/images/بلوزة صفرا.png') }}">
                <div>E</div>
            </div>
            <div class="image-card">
                <img src="{{ asset('img/images/قميص أزرق.png') }}">
                <div>F</div>
            </div>
            <div class="image-card">
                <img src="{{ asset('img/images/بنطلون أسود.png') }}">
                <div>G</div>
            </div>
            <div class="image-card">
                <img src="{{ asset('img/images/طاقيّة بيضا.png') }}">
                <div>H</div>
            </div>
        </div>
    </div>

    <div class="question-group">
        <x-activity-fill
            que="بدّي الطاقيّة السودا، لو سمحت"
            ans="A"/>
        <x-activity-fill
            que="بدّي البنطلون الأخضر، لو سمحت"
            ans="B"/>
        <x-activity-fill
            que="بدّي البلوزة الحمرا، لو سمحت"
            ans="C"/>
        <x-activity-fill
            que="بدّي القميص الأبيض، لو سمحت"
            ans="D"/>
        <x-activity-fill
            que="بدّي البلوزة الصفرا، لو سمحت"
            ans="E"/>
        <x-activity-fill
            que="بدّي القميص الأزرق، لو سمحت"
            ans="F"/>
        <x-activity-fill
            que="بدّي البنطلون الأسود، لو سمحت"
            ans="G"/>
        <x-activity-fill
            que="بدّي الطاقيّة البيضا، لو سمحت"
            ans="H"/>
    </div>
</x-activity-area>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">

</x-lesson-concept>
