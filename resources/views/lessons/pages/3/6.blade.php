<x-vue.deck component="DeckContainer" :deck="\App\Models\Deck::find(62)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>Prepositionally, <b>إلـ (ʔil-)</b> refers to the possessor or beneficiary of something.</p>
    <x-sentence-item eng="there is a friend of his">
        <x-sentence-term arb="فيه" eng="there is" :term="$terms['fīh'] ?? null"/>
        <x-sentence-term arb="صديق" eng="a friend" :term="$terms['ṣadīq'] ?? null"/>
        <x-sentence-term arb="إله" eng="of-his" :term="$terms['la-'] ?? null"/>
    </x-sentence-item>
    <p>Combining <b>فيه (fīh)</b> & <b>إلـ (ʔil-)</b> creates <b>"to have"</b> in reference to things that are not
        "owned" but belong intrinsically to the subject, including kin relations.</p>
    <x-sentence-item eng="he has a friend">
        <x-sentence-term arb="فيه إله" eng="3M.has" :term="$terms['la-'] ?? null"/>
        <x-sentence-term arb="صديق" eng="a friend" :term="$terms['ṣadīq'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="this word has several meanings">
        <x-sentence-term arb="هالكلمة" eng="this-word" :term="$terms['kilme'] ?? null"/>
        <x-sentence-term arb="فيه إلها" eng="3F.has" :term="$terms['la-'] ?? null"/>
        <x-sentence-term arb="أكتر" eng="more" :term="$terms['ktīr'] ?? null"/>
        <x-sentence-term arb="من" eng="than" :term="$terms['min'] ?? null"/>
        <x-sentence-term arb="معنى" eng="one meaning" :term="$terms['maʕna'] ?? null"/>
    </x-sentence-item>
    <p>We can use <b>فيه إله (fīh ʔilo)</b> with or without <b>فيه (fīh)</b>. If <b>فيه (fīh)</b> is used, it is negated
        by <b>فش (fiš)</b>. Otherwise, the preposition is negated verbally, with a slightly different pronunciation.</p>
    <x-sentence-item eng="he has no friends">
        <x-sentence-term arb="فش إله" eng="3M.has no" :term="$terms['la-'] ?? null"/>
        <x-sentence-term arb="صحاب" eng="friends" :term="$terms['ṣāħib'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="he has nothing to do with it">
        <x-sentence-term arb="مالوش" eng="3M.has no" :term="$terms['la-'] ?? null"/>
        <x-sentence-term arb="علاقة" eng="relationship" :term="$terms['ʕalāqa'] ?? null"/>
        <x-sentence-term arb="بالموضوع" eng="in-the-subject" :term="$terms['mawḍūʕ'] ?? null"/>
    </x-sentence-item>
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">
</x-lesson-concept>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">
</x-lesson-concept>
