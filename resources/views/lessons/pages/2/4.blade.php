<x-deck-container :deck="\App\Models\Deck::find(50)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>We can convey our impressions about something by prefacing our thoughts with the <b>pseudo-verb</b> <b>شكله
            (šiklo "it seems")</b>. It may be used neutrally, as in this sentence:</p>
    <x-sentence-item eng="it seems his father is Palestinian">
        <x-sentence-term arb="شكله" eng="it seems" :term="$terms['šiklo'] ?? null"/>
        <x-sentence-term arb="أبوه" eng="his-father" :term="$terms['ʔabu'] ?? null"/>
        <x-sentence-term arb="فلسطينيّ" eng="Palestinian" :term="$terms['falasṭīni'] ?? null"/>
    </x-sentence-item>

    <p>It's also possible to specify a point of reference using the correct inflection. In that case, it's preferable
        for the verb to follow the subject, if there is one.</p>
    <x-sentence-item eng="his father seems Palestinian">
        <x-sentence-term arb="أبوه" eng="his-father" :term="$terms['ʔabu'] ?? null"/>
        <x-sentence-term arb="شكله" eng="3M.seems" :term="$terms['šiklo'] ?? null"/>
        <x-sentence-term arb="فلسطينيّ" eng="Palestinian" :term="$terms['falasṭīni'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="you seem upset">
        <x-sentence-term arb="شكلك" eng="2M.seem" :term="$terms['šiklo'] ?? null"/>
        <x-sentence-term arb="زعلان" eng="upset" :term="$terms['zaʕlān'] ?? null"/>
    </x-sentence-item>

    <x-inflections
        conj1S="شكلي" conj1Str="šikli"
        conj1P="شكلنا" conj1Ptr="šiklna"
        conj2M="شكلك" conj2Mtr="šiklak"
        conj2F="شكلك" conj2Ftr="šiklek"
        conj2P="شكلكم" conj2Ptr="šiklkom"
        conj3M="شكله" conj3Mtr="šiklo"
        conj3F="شكلها" conj3Ftr="šiklha"
        conj3P="شكلهم" conj3Ptr="šiklhom"
    ></x-inflections>
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

    <p>Sometimes two adjectives that seem to have the same meaning aren't used in the same context, as some adjectives
        specifically apply to people & can't be used for inanimate nouns. One type of adjective — the <b>Intensive
            Adjective</b> — usually refers to feelings, so is mostly reserved for animate nouns. Notice that by
        definition they are in the <b>فعلان (faʕlān)</b> pattern.</p>

    <div class="array">
        <x-sentence-item eng="Sama is (feeling) cold">
            <x-sentence-term arb="سما" eng="Sama"/>
            <x-sentence-term arb="بردانة" eng="cold" :term="$terms['bardān'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="Sama is upset">
            <x-sentence-term arb="سما" eng="Sama"/>
            <x-sentence-term arb="زعلانة" eng="upset" :term="$terms['zaʕlān'] ?? null"/>
        </x-sentence-item>
    </div>

    <p>When it comes to animate nouns, <b>Intensive Adjectives</b> — like <b>Active Participles</b> — are used to
        indicate someone's current state, rather than an essential quality of theirs.</p>

    <div class="array">
        <x-sentence-item eng="? Sama is (a) cold (person)">
            <x-sentence-term arb="سما" eng="Sama"/>
            <x-sentence-term arb="باردة" eng="cold" :term="$terms['bārid'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="the coffee is cold">
            <x-sentence-term arb="القهوة" eng="the-coffee" :term="$terms['ʔahwe'] ?? null"/>
            <x-sentence-term arb="باردة" eng="cold" :term="$terms['bārid'] ?? null"/>
        </x-sentence-item>
    </div>
</x-lesson-concept>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">
    <p>We can express surprise & wonderment at something with <b>معقول (maʕʔūl "could it be")</b>. On its own, it's
        interchangeable with expressions like <b>عن جدّ (ʕan žadd "really")</b>, but it may be used to put forward a
        hypothesis as well; in this sense, it's similar to <b>يمكن (yimkin "maybe")</b>, but it conveys a sense of
        surprise toward the idea in question.</p>

    <div class="dialog-body">
        <x-dialog-line speaker="أكرم" arb="وين غسّان؟" eng="where's Ghassan?"/>
        <x-dialog-line speaker="سما" arb="معقول عند أهله؟" eng="could it be he's at his parents'?"/>
        <x-dialog-line speaker="أكرم" arb="معقول؟" eng="could it be?"/>
    </div>
</x-lesson-concept>
