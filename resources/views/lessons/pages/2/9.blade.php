<x-deck-container :deck="\App\Models\Deck::find(55)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">
</x-lesson-concept>

<x-lesson-concept section-type="idea" section-title="know this"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">

    <p>In Arab culture, one's full name is the so-called <b>إسم رباعي (ʔism rubāʕi)</b> — a four-part name
        composed of the given name, the father's given name, the father's father's given name & the family
        name:</p>
    <x-sentence-item eng="Ahmad Odeh Mahmud al-Barghuti">
        <x-sentence-term arb="أحمد" eng="given"/>
        <x-sentence-term arb="عودة" eng="father's"/>
        <x-sentence-term arb="محمود" eng="g-father's"/>
        <x-sentence-term arb="البرغوثي" eng="family"/>
    </x-sentence-item>
</x-lesson-concept>

<x-activity-area title="{{ __('dialogue') }}">
    <p>Shadow the following conversation between ? & ? in ?:</p>
    <div class="activity-dialog">
        <x-dialog-line speaker="غسّان" arb="يعطيك العافية"/>
        <x-dialog-line speaker="جريس" arb="الله يعافيك"/>
        <x-dialog-line speaker="غسّان" arb="بدّي أغلّبك"/>
        <x-dialog-line speaker="جريس" arb="تفضّل"/>
        <x-dialog-line speaker="غسّان" arb="بدّي صحن حمّص، حبّتين فلافل، وكاسة شاي، لو سمحت"/>
        <x-dialog-line speaker="جريس" arb="تمام"/>
    </div>
</x-activity-area>
