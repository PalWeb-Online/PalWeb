<x-deck-container :deck="\App\Models\Deck::find(51)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>Aside from <b>1-10</b>, only two other unique numeral sets exist, although they too are formed from the single
        digits: generally speaking, <b>11-19</b> are formed by attaching <b>عش (-aš)</b> — from <b>عشرة (ʕašara)</b> —
        to the single digits, while intervals of 10 (i.e. <b>20, 30, etc.</b>) attach the suffix <b>ين (-īn)</b> to the
        single digits.</p>
    <x-sentence-item eng="fifteen">
        <x-sentence-term arb="خمستعش" eng="fifteen" :term="$terms['xamstāš'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="fifty">
        <x-sentence-term arb="خمسين" eng="fifty" :term="$terms['xamsīn'] ?? null"/>
    </x-sentence-item>

    <p>All remaining numbers until 99 are formed by linking these together with <b>و (w)</b>. However, the digit order
        is reversed: in Arabic, <b>"fifty-five"</b> would be <b>"five & fifty"</b>.</p>
    <x-sentence-item eng="fifty-five">
        <x-sentence-term arb="خمسة" eng="five" :term="$terms['xamse'] ?? null"/>
        <x-sentence-term arb="و" eng="&" :term="$terms['w'] ?? null"/>
        <x-sentence-term arb="خمسين" eng="fifty" :term="$terms['xamsīn'] ?? null"/>
    </x-sentence-item>

    {{--    <h1>EXAMPLES</h1>--}}
    {{--    <p>Read the following text:</p>--}}
    {{--    <div class="arbtr">كولومبيا بلد كبيرة كتير. فيه أكتر من خمسين مليون شخص ساكنين فيها. بس هي كتير بعيدة عن فلسطين.--}}
    {{--        مدّة الرحلة بين فلسطين وكولومبيا تقريبا أربعة وعشرين ساعة. فيه لكولومبيا أكتر من منطقة، والطقس مختلف بكلّ منطقة.--}}
    {{--        متلا، الدنبا شوب دايما عند البحر، أمّا بالجبل هي مرّات برد كتير. قدّيش عدد سكّان بلدك؟ فيه إلها مناطق مختلفة؟--}}
    {{--    </div>--}}
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

    <p>In Standard Arabic, the months of the Gregorian calendar — a type of Christian calendar — are known by a variety
        of names, depending on the country. In Palestine, they are colloquially known simply according to their
        numerical order.</p>
    <x-sentence-item eng="July">
        <x-sentence-term arb="شهر" eng="month" :term="$terms['šahr'] ?? null"/>
        <x-sentence-term arb="سبعة" eng="seven" :term="$terms['sabaʕa'] ?? null"/>
    </x-sentence-item>
    <p>When referring to a calendar date, it's verbalized in the <b>DD-MM-(YY)YY</b> format simply as numbers
        back-to-back; the word <b>شهر (šahr)</b> may be optionally ommitted.</p>
    <x-sentence-item eng="July 3rd">
        <x-sentence-term arb="ثلاثة" eng="three" :term="$terms['talāte'] ?? null"/>
        <x-sentence-term arb="سبعة" eng="seven" :term="$terms['sabaʕa'] ?? null"/>
    </x-sentence-item>

</x-lesson-concept>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">

    <p>Probably the most important transition word in Palestinian Arabic is <b>فـ (fa- "so, then")</b> — another clitic.
        Often used to introduce the second part of a conditional <b>if-then</b> statement, we'll used it now simply to
        introduce an idea that follows as a result of something else.</p>

    <x-sentence-item eng="tomorrow is my friend's birthday, so I need a gift for him">
        <x-sentence-term arb="بكرا" eng="tomorrow" :term="$terms['bukra'] ?? null"/>
        <x-sentence-term arb="عيد ميلاد" eng="birthday" :term="$terms['ʕīd mīlād'] ?? null"/>
        <x-sentence-term arb="صاحبي" eng="my-friend" :term="$terms['ṣāħib'] ?? null"/>
        <x-sentence-term arb="فـ" eng="so" :term="$terms['fa-'] ?? null"/>
        <x-sentence-term arb="ـبدّي" eng="1S.want" :term="$terms['biddo'] ?? null"/>
        <x-sentence-term arb="هديّة" eng="gift" :term="$terms['hadiyye'] ?? null"/>
        <x-sentence-term arb="لإله" eng="for-him" :term="$terms['la-'] ?? null"/>
    </x-sentence-item>
</x-lesson-concept>
