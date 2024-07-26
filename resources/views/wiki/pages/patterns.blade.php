<x-page-head title="{{ __('patterns') }}">
    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>
    <a>{{ __('morphology') }}</a>
    <x-link :href="route('wiki.show', 'patterns')">{{ __('patterns') }}</x-link>
</x-page-head>

<div class="doc-section">
    <h1>Singular Patterns</h1>

    <h2 id="CvCC" style="scroll-margin: 8rem">CCC</h2>
    <x-vocabulary title="CLC">
        <x-term :term="$terms->firstWhere('translit', 'bāb')"/>
        <x-term :term="$terms->firstWhere('translit', 'sūʔ')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʕīd')"/>
    </x-vocabulary>
    <x-vocabulary title="CvCC">
        <x-term :term="$terms->firstWhere('translit', 'šams')"/>
        <x-term :term="$terms->firstWhere('translit', 'bēt')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṣaff')"/>
    </x-vocabulary>
    <x-vocabulary title="CvCCe">
        <x-term :term="$terms->firstWhere('translit', 'ġurfe')"/>
        <x-term :term="$terms->firstWhere('translit', 'dōle')"/>
        <x-term :term="$terms->firstWhere('translit', 'šaʔʔa')"/>
    </x-vocabulary>
    <x-vocabulary title="CvCvC">
        <x-term :term="$terms->firstWhere('translit', 'balad')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔamar')"/>
        <x-term :term="$terms->firstWhere('translit', 'zalame')"/>
    </x-vocabulary>
    {{--    <x-vocabulary title="plural: CCūC">--}}
    {{--        <x-inflections--}}
    {{--            conjF="درس" conjFtr="dars"--}}
    {{--            conjP="دروس" conjPtr="drūs"--}}
    {{--        ></x-inflections>--}}
    {{--        <x-inflections--}}
    {{--            conjF="بيت" conjFtr="bēt"--}}
    {{--            conjP="بيوت" conjPtr="byūṭ"--}}
    {{--        ></x-inflections>--}}
    {{--        <x-inflections--}}
    {{--            conjF="صفّ" conjFtr="ṣaff"--}}
    {{--            conjP="صفوف" conjPtr="ṣfūf"--}}
    {{--        ></x-inflections>--}}
    {{--    </x-vocabulary>--}}
    {{--    <x-vocabulary>--}}
    {{--        <x-term arb="CvCaC" eng="CvCCe broken plural pattern"/>--}}
    {{--        <x-inflections--}}
    {{--            conjF="غرفة" conjFtr="ġurfe"--}}
    {{--            conjP="غرف" conjPtr="ġuraf"--}}
    {{--        ></x-inflections>--}}
    {{--        <x-inflections--}}
    {{--            conjF="بركة" conjFtr="birke"--}}
    {{--            conjP="برك" conjPtr="birak"--}}
    {{--        ></x-inflections>--}}
    {{--        <x-inflections--}}
    {{--            conjF="شقّة" conjFtr="šaʔʔa"--}}
    {{--            conjP="شقق" conjPtr="šuʔaʔ"--}}
    {{--        ></x-inflections>--}}
    {{--    </x-vocabulary>--}}

    <h2>CCLC</h2>
    <x-vocabulary title="CCāC">
        <x-term :term="$terms->firstWhere('translit', 'ktāb')"/>
        <x-term :term="$terms->firstWhere('translit', 'buxār')"/>
        <x-term :term="$terms->firstWhere('translit', 'žihāz')"/>
    </x-vocabulary>
    <x-vocabulary title="CCīC">
        <x-term :term="$terms->firstWhere('translit', 'rġīf')"/>
        <x-term :term="$terms->firstWhere('translit', 'ṣadīq')"/>
        <x-term :term="$terms->firstWhere('translit', 'madīne')"/>
    </x-vocabulary>
    <x-vocabulary title="CCūC">
        <x-term :term="$terms->firstWhere('translit', 'zbūn')"/>
        <x-term :term="$terms->firstWhere('translit', 'xarūf')"/>
        <x-term :term="$terms->firstWhere('translit', 'sxūne')"/>
    </x-vocabulary>

    <h2>CCCC</h2>
    <x-vocabulary title="CvCCvC">
        <x-term :term="$terms->firstWhere('translit', 'zaʕtar')"/>
        <x-term :term="$terms->firstWhere('translit', 'taksy')"/>
        <x-term :term="$terms->firstWhere('translit', 'silsile')"/>
        <x-term :term="$terms->firstWhere('translit', 'muškile')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔuġnye')"/>
    </x-vocabulary>

    <h2>CCCLC</h2>
    <x-vocabulary title="CvCCLC">
        <x-term :term="$terms->firstWhere('translit', 'ʔusbūʕ')"/>
        <x-term :term="$terms->firstWhere('translit', 'xityār')"/>
        <x-term :term="$terms->firstWhere('translit', 'ʔannīne')"/>
        <x-term :term="$terms->firstWhere('translit', 'muftāħ')"/>
        <x-term :term="$terms->firstWhere('translit', 'duktōr')"/>
    </x-vocabulary>
</div>

<div class="doc-section">
    <h1>Plural Patterns</h1>

    <h2 id="CvCC" style="scroll-margin: 8rem">CCC</h2>
    <x-vocabulary title="CCāC">
        <x-term :term="$terms->firstWhere('translit', 'kalb')"/>
        <x-term subterm arb="كلاب" eng="dogs"/>
        <x-term :term="$terms->firstWhere('translit', 'ḍaww')"/>
        <x-term subterm arb="ضواو" eng="lights"/>
        <x-term :term="$terms->firstWhere('translit', 'walad')"/>
        <x-term subterm arb="ولاد" eng="boys; children"/>
    </x-vocabulary>
</div>

<div class="box borderless">
    <div class="preamble">
        <h1>{{ __('wiki') }} | توثيق</h1>
        <h1>word formation</h1>
    </div>
    <div class="h-badge">
        <div class="badge h1">
            <div class="h">named patterns</div>
        </div>
    </div>

    <h1>NOUN</h1>
    <h2>place: ma12a3 & ma12i3</h2>
    <p>This is a subset of the quadriliteral <b>CvCCvC</b> pattern & hence has <b>CaCāCiC</b> plurals.</p>

    <h1>ADJECTIVE</h1>
    <h2>agent: 1a22ā3</h2>
    <p>This is a subset of the quadriliteral <b>CvCCLC</b> pattern, but it has sound plurals given that it is an
        adjective pattern in principle.</p>

    <h2>intensive adjective: 1a23ān</h2>

</div>

<div class="box borderless">
    <div class="preamble">
        <h1>{{ __('wiki') }} | توثيق</h1>
        <h1>word formation</h1>
    </div>
    <div class="h-badge">
        <div class="badge h1">
            <div class="h">affix patterns</div>
        </div>
    </div>

    <h2>relative adjective: -iyy</h2>

    <h2>nominalized adjective: -iyye</h2>

</div>
