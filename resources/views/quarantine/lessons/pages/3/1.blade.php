<x-vue.deck component="DeckContainer" :deck="\App\Models\Deck::find(57)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">
    <p>We've learned that <b>nominal sentences</b> are negated by <b>مش (miš "not")</b>:</p>
    <x-sentence-item eng="I am not Rafiq">
        <x-sentence-term arb="أنا" eng="I" :term="$terms['ʔana'] ?? null"/>
        <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null"/>
        <x-sentence-term arb="رفيق" eng="Rafiq"/>
    </x-sentence-item>
    <p>We also learned that <b>فيه (fīh "there is")</b> is negated by <b>فش (fiš "there is no")</b>:</p>
    <x-sentence-item eng="I don't have any food">
        <x-sentence-term arb="فش عندي" eng="I don't have" :term="$terms['ʔind'] ?? null"/>
        <x-sentence-term arb="أكل" eng="food" :term="$terms['ʔakl'] ?? null"/>
    </x-sentence-item>
    <p>Finally, verbal sentences are negated by wrapping the verb between <b>ما (mā)</b> & <b>ـش (-š)</b>:</p>
    <x-sentence-item eng="I don't have any food">
        <x-sentence-term arb="ما عنديش" eng="I don't have" :term="$terms['ʔind'] ?? null"/>
        <x-sentence-term arb="أكل" eng="food" :term="$terms['ʔakl'] ?? null"/>
    </x-sentence-item>
    <p>(It is possible to use only <b>ما (mā)</b> for negation — without <b>ـش (-š)</b> — but this is less common. On
        the other hand, using only <b>ـش (-š)</b> — without <b>ما (mā)</b> — is the most common negation paradigm;
        however, there are times that its use is completely ungrammatical. Therefore, we will stick to using <b>ما ـش
            (mā -š)</b> together.)</p>
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

</x-lesson-concept>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">

</x-lesson-concept>
