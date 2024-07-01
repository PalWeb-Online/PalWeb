<x-page-head title="{{ __('numerals') }}" blurb="Numerals are adjectives that can head a counting clause & are not necessarily subject to grammatical
    agreement.">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <x-link :href="route('wiki.show', 'syntax')">{{ __('syntax') }}</x-link>
    <x-link :href="route('wiki.show', 'numerals')">{{ __('numerals') }}</x-link>
</x-page-head>

<p>As a grammatical category, <b>Numerals</b> is not merely a semantic grouping of number terms, but is more generally a
    grouping of terms indicating quantity or degree that have similar syntactic behaviors.</p>

<p>At first glance, semantic numbers can function syntactically like adjectives; in that sense, it could be said
    that they belong to the <b>Adjective</b> grammatical category.</p>

<x-sentence eng="the young students">
    <x-sentence-term arb="الطلّاب" eng="the-students" :term="$terms->firstWhere('translit', 'ṭālib')"/>
    <x-sentence-term arb="الزغار" eng="the-young" :term="$terms->firstWhere('translit', 'zḡīr')"/>
</x-sentence>
<x-sentence eng="the five students">
    <x-sentence-term arb="الطلّاب" eng="the-students" :term="$terms->firstWhere('translit', 'ṭālib')"/>
    <x-sentence-term arb="الخمسة" eng="the-five" :term="$terms->firstWhere('translit', 'xamse')"/>
</x-sentence>
<x-sentence eng="the fifth student">
    <x-sentence-term arb="الطالب" eng="the-student" :term="$terms->firstWhere('translit', 'ṭālib')"/>
    <x-sentence-term arb="الخامس" eng="the-fifth" :term="$terms->firstWhere('translit', 'xāmis')"/>
</x-sentence>

<p>However, as a grammatical category, <b>Numerals</b> are defined by their ability to instantiate
    the so-called <b>counting structure</b> (<b>هيكل العدّ</b>). Although all these terms are classified only as <b>Numerals</b>
    for simplicity, it should be kept in mind that they are always viably <b>Adjectives</b> as well.</p>

<x-sentence eng="five students">
    <x-sentence-term arb="خمس" eng="five" :term="$terms->firstWhere('translit', 'xamse')"/>
    <x-sentence-term arb="طلّاب" eng="students" :term="$terms->firstWhere('translit', 'ṭālib')"/>
</x-sentence>
<x-sentence eng="the fifth student">
    <x-sentence-term arb="خامس" eng="fifth" :term="$terms->firstWhere('translit', 'xāmis')"/>
    <x-sentence-term arb="طالب" eng="student" :term="$terms->firstWhere('translit', 'ṭālib')"/>
</x-sentence>

<p>Although this structure is referred to as defining of <b>Numerals</b>, <b>elative adjectives</b> — like semantic <b>numerals</b>
    — may be used both as <b>attributive adjectives</b> & as the head term of the <b>counting structure</b> as well.
    Since they share all the features of semantic <b>numerals</b>, <b>elative adjectives</b> are likewise classified as
    <b>Numerals</b> — keeping in mind that all <b>Numerals</b> are always viably <b>Adjectives</b> as well.</p>

<x-sentence eng="the smartest student">
    <x-sentence-term arb="الطالب" eng="the-student" :term="$terms->firstWhere('translit', 'ṭālib')"/>
    <x-sentence-term arb="الأشطر" eng="the-smartest" :term="$terms->firstWhere('translit', 'šāṭir')"/>
</x-sentence>
<x-sentence eng="the smartest student">
    <x-sentence-term arb="أشطر" eng="smartest" :term="$terms->firstWhere('translit', 'šāṭir')"/>
    <x-sentence-term arb="طالب" eng="student" :term="$terms->firstWhere('translit', 'ṭālib')"/>
</x-sentence>

<p>In one sense, <b>elative adjectives</b> are a type of <b>ordinal number</b>; while the canonical <b>ordinal
        numbers</b> refer to an endpoint in a numerical sequence (e.g. "the fifth"), <b>elative adjectives</b> have
    their endpoint in a
    qualitative maximum (e.g. "the smartest").</p>

<p>Although the number of non-compound numbers is
    finite, the grammatical category of <b>Numerals</b> is considered an open category insofar as it contains the <b>elative</b>
    forms of all <b>Adjectives</b>.</p>

<div class="doc-section">
    <h1>Numbers</h1>
    <p><b>Cardinal Numbers</b> may be grouped in the following sets:</p>
    <ul>
        <li><b>1</b> – <b>10</b></li>
        <li><b>11</b> – <b>19</b></li>
        <li>multiples of <b>10</b> (i.e. <b>20</b>, <b>30</b> ... <b>90</b>)</li>
        <li><b>صفر</b> ("zero"), <b>ميّة</b> ("hundred"), <b>ألف</b> ("thousand") & <b>مليون</b>
            ("million")
        </li>
    </ul>
    <p>Alongside all terms ending in <b>ة (-e)</b>, the sets of <b>3-10</b> & <b>11-19</b> are the only terms in
        Palestinian Arabic with <b>construct forms</b>. Meanwhile,
        <b>واحد (wāħad)</b> & <b>ثنين (tnēn)</b> are the only two numbers with <b>gendered forms</b> rather than <b>construct
            forms</b>.</p>

    <p>All other cardinal numbers are built from these sets & are thus not indexed independently.</p>

    <p><b>Ordinal Numbers</b> are limited only to the first ten positions, with an additional term for the final
        position; there is no productive way to form higher ordinals.</p>
</div>

<div class="doc-section">
    <h1>Adjectives</h1>
    <p>Although the <b>elative</b> forms of all <b>Adjectives</b> fall within this category, they are not
        individually recorded as distinct lemmas unless they are not related to any <b>positive</b> form — with the
        exception of the analytic elativizer <b>أكتر (ʔaktar)</b>.</p>
    <x-sentence eng="the thing you like to do the most">
        <x-sentence-term arb="أكتر" eng="most" :term="$terms->firstWhere('translit', 'ʔaktar')"/>
        <x-sentence-term arb="إشي بتحبّ تسوّيه" eng="something you like to do"/>
    </x-sentence>
    <x-vocabulary title="elatives">
        <x-term :term="$terms->firstWhere('translit', 'ʔaktar')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔaħsan')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔafḍal')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔaqṣa')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔadna')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔasfal')"/>
    </x-vocabulary>
</div>
