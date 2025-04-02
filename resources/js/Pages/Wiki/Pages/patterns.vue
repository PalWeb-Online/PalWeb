<script setup>
import {route} from "ziggy-js";
import Wiki from "../Wiki.vue";
import Layout from "../../../Shared/Layout.vue";

defineOptions({
    layout: [Layout, Wiki],
})
</script>
<template>
    <div class="wiki-content-block">
        <h1>Singular Patterns</h1>

<!--        <h2 id="CvCC" style="scroll-margin: 8rem">CCC</h2>-->
<!--        <x-vocabulary title="CLC">-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'bāb')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'sūʔ')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'ʕīd')"/>-->
<!--        </x-vocabulary>-->
<!--        <x-vocabulary title="CvCC">-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'šams')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'bēt')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'ṣaff')"/>-->
<!--        </x-vocabulary>-->
<!--        <x-vocabulary title="CvCCe">-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'ġurfe')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'dōle')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'šaʔʔa')"/>-->
<!--        </x-vocabulary>-->
<!--        <x-vocabulary title="CvCvC">-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'balad')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'ʔamar')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'zalame')"/>-->
<!--        </x-vocabulary>-->
<!--        <x-vocabulary title="plural: CCūC">-->
<!--            <x-inflections conjF="درس" conjFtr="dars" conjP="دروس" conjPtr="drūs"></x-inflections>-->
<!--            <x-inflections conjF="بيت" conjFtr="bēt" conjP="بيوت" conjPtr="byūṭ"></x-inflections>-->
<!--            <x-inflections conjF="صفّ" conjFtr="ṣaff" conjP="صفوف" conjPtr="ṣfūf"></x-inflections>-->
<!--        </x-vocabulary>-->
<!--        <x-vocabulary>-->
<!--            <x-term-item arb="CvCaC" eng="CvCCe broken plural pattern"/>-->
<!--            <x-inflections conjF="غرفة" conjFtr="ġurfe" conjP="غرف" conjPtr="ġuraf"></x-inflections>-->
<!--            <x-inflections conjF="بركة" conjFtr="birke" conjP="برك" conjPtr="birak"></x-inflections>-->
<!--            <x-inflections conjF="شقّة" conjFtr="šaʔʔa" conjP="شقق" conjPtr="šuʔaʔ"></x-inflections>-->
<!--        </x-vocabulary>-->

<!--        <h2>CCLC</h2>-->
<!--        <x-vocabulary title="CCāC">-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'ktāb')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'buxār')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'žihāz')"/>-->
<!--        </x-vocabulary>-->
<!--        <x-vocabulary title="CCīC">-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'rġīf')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'ṣadīq')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'madīne')"/>-->
<!--        </x-vocabulary>-->
<!--        <x-vocabulary title="CCūC">-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'zbūn')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'xarūf')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'sxūne')"/>-->
<!--        </x-vocabulary>-->

<!--        <h2>CCCC</h2>-->
<!--        <x-vocabulary title="CvCCvC">-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'zaʕtar')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'taksy')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'silsile')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'muškile')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'ʔuġnye')"/>-->
<!--        </x-vocabulary>-->

<!--        <h2>CCCLC</h2>-->
<!--        <x-vocabulary title="CvCCLC">-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'ʔusbūʕ')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'xityār')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'ʔannīne')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'muftāħ')"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'duktōr')"/>-->
<!--        </x-vocabulary>-->
    </div>

    <div class="wiki-content-block">
        <h1>Plural Patterns</h1>

<!--        <h2 id="CvCC" style="scroll-margin: 8rem">CCC</h2>-->
<!--        <x-vocabulary title="CCāC">-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'kalb')"/>-->
<!--            <x-term-item subterm arb="كلاب" eng="dogs"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'ḍaww')"/>-->
<!--            <x-term-item subterm arb="ضواو" eng="lights"/>-->
<!--            <x-term-item :term="$terms->firstWhere('translit', 'walad')"/>-->
<!--            <x-term-item subterm arb="ولاد" eng="boys; children"/>-->
<!--        </x-vocabulary>-->

<!--        <div class="box borderless">-->
<!--            <div class="preamble">-->
<!--                <h1>wiki | توثيق</h1>-->
<!--                <h1>word formation</h1>-->
<!--            </div>-->
<!--            <div class="h-badge">-->
<!--                <div class="badge h1">-->
<!--                    <div class="h">named patterns</div>-->
<!--                </div>-->
<!--            </div>-->

<!--            <h1>NOUN</h1>-->
<!--            <h2>place: ma12a3 & ma12i3</h2>-->
<!--            <p>This is a subset of the quadriliteral <b>CvCCvC</b> pattern & hence has <b>CaCāCiC</b> plurals.</p>-->

<!--            <h1>ADJECTIVE</h1>-->
<!--            <h2>agent: 1a22ā3</h2>-->
<!--            <p>This is a subset of the quadriliteral <b>CvCCLC</b> pattern, but it has sound plurals given that it is an-->
<!--                adjective pattern in principle.</p>-->

<!--            <h2>intensive adjective: 1a23ān</h2>-->
<!--        </div>-->

<!--        <div class="box borderless">-->
<!--            <div class="preamble">-->
<!--                <h1>wiki | توثيق</h1>-->
<!--                <h1>word formation</h1>-->
<!--            </div>-->
<!--            <div class="h-badge">-->
<!--                <div class="badge h1">-->
<!--                    <div class="h">affix patterns</div>-->
<!--                </div>-->
<!--            </div>-->

<!--            <h2>relative adjective: -iyy</h2>-->
<!--            <h2>nominalized adjective: -iyye</h2>-->
<!--        </div>-->
    </div>
</template>

