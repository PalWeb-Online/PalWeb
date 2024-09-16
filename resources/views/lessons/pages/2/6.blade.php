<x-deck-container :deck="\App\Models\Deck::find(52)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>In Arabic, nouns that are the target of prepositions are in the <b>genitive-accusative</b> case as well. In
        practical terms, this means that we use the <b>clitic form</b> of pronouns with prepositions. Note that many
        prepositions have a slightly different <b>host form</b> that accepts <b>clitic pronouns</b>:</p>
    <x-sentence-item eng="he is older than him">
        <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
        <x-sentence-term arb="أكبر" eng="older" :term="$terms['kbīr'] ?? null"/>
        <x-sentence-term arb="منّـ" eng="than" :term="$terms['min'] ?? null"/>
        <x-sentence-term arb="ـه" eng="him" :term="$terms['-o'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="his son is like him">
        <x-sentence-term arb="ولده" eng="his-son" :term="$terms['walad'] ?? null"/>
        <x-sentence-term arb="زيّه" eng="like-him" :term="$terms['zayy'] ?? null"/>
    </x-sentence-item>

    <p>We use <b>clitic pronouns</b> with the following two adverbial question words as well:</p>
    <x-sentence-item eng="how are you?">
        <x-sentence-term arb="كيفـ" eng="how" :term="$terms['kīf'] ?? null"/>
        <x-sentence-term arb="ـك؟" eng="you" :term="$terms['-ak'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="where are you?">
        <x-sentence-term arb="وينـ" eng="where" :term="$terms['wēn'] ?? null"/>
        <x-sentence-term arb="ـك؟" eng="you" :term="$terms['-ak'] ?? null"/>
    </x-sentence-item>

</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

    <p>We use the preposition <b>لـ (la- "to")</b> to state that something belongs to someone:</p>
    <x-sentence-item eng="whose car is it?">
        <x-sentence-term arb="لـ" eng="to" :term="$terms['ʔil-'] ?? null"/>
        <x-sentence-term arb="مين" eng="who" :term="$terms['mīn'] ?? null"/>
        <x-sentence-term arb="السيّارة؟" eng="the-car" :term="$terms['sayyāra'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="the car is Baraa's">
        <x-sentence-term arb="السيّارة" eng="the-car" :term="$terms['sayyāra'] ?? null"/>
        <x-sentence-term arb="لـ" eng="to" :term="$terms['ʔil-'] ?? null"/>
        <x-sentence-term arb="براء" eng="Baraa"/>
    </x-sentence-item>

    <p>We can attach a <b>clitic pronoun</b> to <b>لـ (la-)</b> to create terms like <b>"mine"</b>:</p>
    <x-sentence-item eng="the car is mine">
        <x-sentence-term arb="السيّارة" eng="the-car" :term="$terms['sayyāra'] ?? null"/>
        <x-sentence-term arb="إلـ" eng="to" :term="$terms['ʔil-'] ?? null"/>
        <x-sentence-term arb="ـي" eng="me" :term="$terms['-i'] ?? null"/>
    </x-sentence-item>

</x-lesson-concept>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">

    <p>We can emphasize a statement by prefacing it with the clitic <b>مـ (ma-)</b>. While this term doesn't have a
        translation in English, it adds a sense of certainty & conviction to a statement that clarifies, explains or
        proves some information previously introduced in the conversation.</p>

    <div class="activity-dialog">
        <x-dialog-line speaker="أكرم" arb="شكله غسّان مش جاي اليوم" eng="it seems Ghassan isn't coming today"/>
        <x-dialog-line speaker="سما" arb="مهو مريض" eng="well, he's sick, after all"/>
    </div>
    <div class="activity-dialog">
        <x-dialog-line speaker="أكرم" arb="العربيّ عندك كثير منيح" eng="your Arabic is really good"/>
        <x-dialog-line speaker="سما" arb="مأنا فلسطينيّ" eng="well, I'm Palestinian, after all"/>
    </div>
    <div class="activity-dialog">
        <x-dialog-line speaker="أكرم" arb="يي — المحلّ مسكّر" eng="oh no, the place is closed"/>
        <x-dialog-line speaker="سما" arb="مالدنيا عيد" eng="well, it's a holiday, after all"/>
    </div>

    <p>Additionally, the phrase <b>مهو (mahuwwe)</b> is commonly used as a standalone form of emphatic agreement,
        indicating that someone else's statement agrees or proves one's prior point.</p>

    <div class="activity-dialog">
        <x-dialog-line speaker="أكرم" arb="معقول المكتبة فاتحة؟" eng="I wonder if the library is open"/>
        <x-dialog-line speaker="سما" arb="أظن لأ، الدنيا ليل" eng="I guess not, it's night-time"/>
        <x-dialog-line speaker="أكرم" arb="مهو" eng="yeah, exactly"/>
    </div>
</x-lesson-concept>
