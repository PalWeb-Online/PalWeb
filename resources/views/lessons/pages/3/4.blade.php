<x-deck :deck="\App\Models\Deck::find(60)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>We are ready to finally learn our first verb: <b>كان (kān "to be")</b>. Notice that the dictionary form of the
        verb is itself the <b>3M (هو) Past Tense</b> conjugation, so <b>كان</b> specifically means <b>"it was"</b>.</p>
    <x-sentence eng="he was in the bathroom">
        <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
        <x-sentence-term arb="كان" eng="3M.was" :term="$terms['kān'] ?? null"/>
        <x-sentence-term arb="بالحمّام" eng="in-the-bathroom" :term="$terms['ħammām'] ?? null"/>
    </x-sentence>
    <x-sentence eng="the movie was nice">
        <x-sentence-term arb="الفلم" eng="the-movie" :term="$terms['film'] ?? null"/>
        <x-sentence-term arb="كان" eng="3M.was" :term="$terms['kān'] ?? null"/>
        <x-sentence-term arb="حلو" eng="nice" :term="$terms['ħilu'] ?? null"/>
    </x-sentence>
    <p>Since true verbs always specify the subject, the subject pronoun is optional.</p>

    <x-inflections title="past"
                   conj3M="كان" conj3Mtr="kān"
    ></x-inflections>

    <p>We can use this form of the verb to put prepositional verbs in the Past Tense:</p>
    <x-sentence eng="I had a question">
        <x-sentence-term arb="كان" eng="was" :term="$terms['kān'] ?? null"/>
        <x-sentence-term arb="عندي" eng="1P.have" :term="$terms['ʕand'] ?? null"/>
        <x-sentence-term arb="سؤال" eng="a question" :term="$terms['suʔāl'] ?? null"/>
    </x-sentence>

</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

    <p>We can use <b>و (w- "while")</b> to refer to a background time that serves as the backdrop of another clause:</p>
    <x-sentence eng="I didn't have many friends when I was young">
        <x-sentence-term arb="ما كانش" eng="wasn't" :term="$terms['kān'] ?? null"/>
        <x-sentence-term arb="عندي" eng="1P.have" :term="$terms['ʕand'] ?? null"/>
        <x-sentence-term arb="صحاب" eng="friends" :term="$terms['ṣāħib'] ?? null"/>
        <x-sentence-term arb="كتير" eng="a lot" :term="$terms['ktīr'] ?? null"/>
        <x-sentence-term arb="وأنا" eng="when-I" :term="$terms['w-'] ?? null"/>
        <x-sentence-term arb="زغير" eng="young" :term="$terms['zḡīr'] ?? null"/>
    </x-sentence>
</x-lesson-concept>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">
    
</x-lesson-concept>
