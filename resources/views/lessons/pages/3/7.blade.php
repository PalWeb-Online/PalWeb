<x-deck :deck="\App\Models\Deck::find(63)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>We've learned that the default form of the verb is the <b>3M (هو) Past Tense</b>. We form the <b>3F (هي) Past
            Tense</b> by adding <b>ـَـت (-at)</b> to the end of the verb, while we form the <b>3P (همّه) Past Tense</b>
        by adding <b>ـو (-u)</b>. Keep in mind that these endings will apply to all verbs — without exception — so get
        used to them!</p>

    <x-inflections title="Be"
                   conjM="كان" conjMtr="kān"
                   conjF="كانت" conjFtr="kānat"
                   conjP="كانو" conjPtr="kānu"
    ></x-inflections>

    <p>Probably the hardest thing in terms of using these two forms is that both can actually refer to things that are
        semantically plural, as inanimate plurals are grammatically feminine.</p>
    <x-sentence eng="she was busy">
        <x-sentence-term arb="هي" eng="she" :term="$terms['hiyye'] ?? null"/>
        <x-sentence-term arb="كانت" eng="3F.was" :term="$terms['kān'] ?? null"/>
        <x-sentence-term arb="مشغولة" eng="busy" :term="$terms['mašḡūl'] ?? null"/>
    </x-sentence>
    <x-sentence eng="the plates were dirty">
        <x-sentence-term arb="الصحون" eng="the-plates" :term="$terms['ṣaħn'] ?? null"/>
        <x-sentence-term arb="كانت" eng="3F.was" :term="$terms['kān'] ?? null"/>
        <x-sentence-term arb="وسخة" eng="dirty" :term="$terms['wisx'] ?? null"/>
    </x-sentence>
    <x-sentence eng="the children were happy">
        <x-sentence-term arb="الولاد" eng="the-children" :term="$terms['walad'] ?? null"/>
        <x-sentence-term arb="كانو" eng="3P.were" :term="$terms['kān'] ?? null"/>
        <x-sentence-term arb="مبسوطين" eng="happy" :term="$terms['mabsūṭ'] ?? null"/>
    </x-sentence>
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">
</x-lesson-concept>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">
</x-lesson-concept>
