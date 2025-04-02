<script setup>
import {route} from "ziggy-js";
import Wiki from "../Wiki.vue";
import Layout from "../../../Shared/Layout.vue";

defineOptions({
    layout: [Layout, Wiki],
})
</script>
<template>
</template>

<!--<x-page-head title="{{ __('verb forms') }}" blurb="There are eight primary verb forms.">-->
<!--    <x-link :href="route('wiki.index')">{{ __('wiki') }}</x-link>-->
<!--    <a>{{ __('morphology') }}</a>-->
<!--    <x-link :href="route('wiki.show', 'verb-forms')">{{ __('forms') }}</x-link>-->
<!--</x-page-head>-->

<!--<div class="wiki-content-block">-->

<!--    <p>The <b>Dictionary</b> distinguishes between eight primary <b>Verb Forms</b>:</p>-->

<!--    <div class="data-box" style="width: 30%">-->
<!--        <h2>FORMS</h2>-->
<!--        <x-inline-chart title="FORM 1" subtitle="عمل (ʕamal)"/>-->
<!--        <x-inline-chart title="FORM 2" subtitle="عمّل (ʕammal)"/>-->
<!--        <x-inline-chart title="FORM 3" subtitle="عامل (ʕāmal)"/>-->
<!--        <x-inline-chart title="FORM 4" subtitle="انعمل (nʕamal)"/>-->
<!--        <x-inline-chart title="FORM 5" subtitle="تعمّل (tʕammal)"/>-->
<!--        <x-inline-chart title="FORM 6" subtitle="تعامل (tʕāmal)"/>-->
<!--        <x-inline-chart title="FORM 7" subtitle="استعمل (staʕmal)"/>-->
<!--        <x-inline-chart title="FORM 8" subtitle="اعتمل (ʕtamal)"/>-->
<!--    </div>-->

<!--    <p><b>Forms 2 & 5</b> provide the template for <b>quadrilireal verbs</b> too:</p>-->

<!--    <div class="data-box" style="width: 30%">-->
<!--        <x-inline-chart title="FORM 2Q" subtitle="جعلك (jaʕlak)"/>-->
<!--        <x-inline-chart title="FORM 5Q" subtitle="تجعلك (tjaʕlak)"/>-->
<!--    </div>-->

<!--    <p>One more rare, non-productive form exists, with only a handful of attested terms:</p>-->
<!--    <div class="data-box" style="width: 30%">-->
<!--        <x-inline-chart title="FORM 9" subtitle="اعملّ (ʕmall)"/>-->
<!--    </div>-->

<!--    <p>Additionally, there is one form that is mainly retained in <b>learned borrowings</b> from <b>Standard-->
<!--            Arabic</b>-->
<!--        & so is not treated as a form native to the dialect, except for exactly one term: <b>أعطى (ʔaʕṭa "to-->
<!--            give")</b>.-->
<!--    </p>-->
<!--    <div class="data-box" style="width: 30%">-->
<!--        <x-inline-chart title="FORM X" subtitle="أعمل (ʔaʕmal)"/>-->
<!--    </div>-->

<!--    <p><b>Forms 4-9</b> all begin with consonant clusters, meaning epenthetic insertion before onset is possible for-->
<!--        all-->
<!--        of them. <b>Forms 5 & 6</b> are thus sometimes represented orthographically with an <b>epenthetic vowel</b>-->
<!--        (e.g. <b>اتحمّم</b>). My decision to retain the prescribed spellings — without removing the <b>epenthetic-->
<!--            vowel</b> altogether — is to reflect mainstream practices & to avoid confusion, as not all speakers-->
<!--        avoid-->
<!--        initial consonant clusters.</p>-->

<!--    <div class="h-badge">-->
<!--        <div class="badge h2">-->
<!--            <div class="h">Morphology</div>-->
<!--        </div>-->
<!--        <div class="badge h2">-->
<!--            <div class="h">Roots</div>-->
<!--        </div>-->
<!--    </div>-->

<!--    <p>Depending on its root, a verb may have an appearance that slightly differs from the base pattern; there are-->
<!--        five-->
<!--        primary <b>Root Types</b> that have an effect on verbal morphology:</p>-->

<!--    <div class="data-box" style="width: 20%">-->
<!--        <h2>ROOTS</h2>-->
<!--        <x-inline-chart title="A-TYPE" subtitle="1 2 3"/>-->
<!--        <x-inline-chart title="B-TYPE" subtitle="1 2 V"/>-->
<!--        <x-inline-chart title="C-TYPE" subtitle="1 2 2"/>-->
<!--        <x-inline-chart title="D-TYPE" subtitle="1 V 3"/>-->
<!--        <x-inline-chart title="E-TYPE" subtitle="V 2 3"/>-->
<!--    </div>-->

<!--    <p>Not all <b>Root Types</b> have an effect on every <b>Verb Form</b>. Here is a complete list of the eight <b>Verb-->
<!--            Forms</b>:</p>-->

<!--    <div class="array" style="justify-content: space-between; margin: 0 9.6rem">-->
<!--        <div class="data-box" style="flex-basis: 45%">-->
<!--            <h2>FORM 1</h2>-->
<!--            <x-inline-chart title="1A" subtitle="عمل (ʕamal)"/>-->
<!--            <x-inline-chart title="1B" subtitle="مشى (maša)"/>-->
<!--            <x-inline-chart title="1C" subtitle="حلّ (ħall)"/>-->
<!--            <x-inline-chart title="1D" subtitle="راح (rāħ)"/>-->
<!--        </div>-->
<!--        <div class="data-box" style="flex-basis: 45%">-->
<!--            <h2>FORM 4</h2>-->
<!--            <x-inline-chart title="4A" subtitle="انعمل (nʕamal)"/>-->
<!--            <x-inline-chart title="4B" subtitle="انمشى (nmaša)"/>-->
<!--            <x-inline-chart title="4C" subtitle="انحلّ (nħall)"/>-->
<!--            <x-inline-chart title="4D" subtitle="انراح (nrāħ)"/>-->
<!--        </div>-->
<!--        <div class="data-box" style="flex-basis: 45%">-->
<!--            <h2>FORM 2</h2>-->
<!--            <x-inline-chart title="2A" subtitle="عمّل (ʕammal)"/>-->
<!--            <x-inline-chart title="2B" subtitle="مشّى (mašša)"/>-->
<!--            <x-inline-chart title="2QA" subtitle="جعلك (jaʕlak)"/>-->
<!--            <x-inline-chart title="2QB" subtitle="فرشى (farša)"/>-->
<!--        </div>-->
<!--        <div class="data-box" style="flex-basis: 45%">-->
<!--            <h2>FORM 5</h2>-->
<!--            <x-inline-chart title="5A" subtitle="تعمّل (tʕammal)"/>-->
<!--            <x-inline-chart title="5B" subtitle="تمشّى (tmašša)"/>-->
<!--            <x-inline-chart title="5QA" subtitle="تجعلك (tjaʕlak)"/>-->
<!--            <x-inline-chart title="5QB" subtitle="تفرشى (tfarša)"/>-->
<!--        </div>-->
<!--        <div class="data-box" style="flex-basis: 45%">-->
<!--            <h2>FORM 3</h2>-->
<!--            <x-inline-chart title="3A" subtitle="عامل (ʕāmal)"/>-->
<!--            <x-inline-chart title="3B" subtitle="ماشى (māša)"/>-->
<!--        </div>-->
<!--        <div class="data-box" style="flex-basis: 45%">-->
<!--            <h2>FORM 6</h2>-->
<!--            <x-inline-chart title="6A" subtitle="تعامل (tʕāmal)"/>-->
<!--            <x-inline-chart title="6B" subtitle="تماشى (tmāša)"/>-->
<!--        </div>-->
<!--        <div class="data-box" style="flex-basis: 45%">-->
<!--            <h2>FORM 7</h2>-->
<!--            <x-inline-chart title="7A" subtitle="استعمل (staʕmal)"/>-->
<!--            <x-inline-chart title="7B" subtitle="استمشى (stamša)"/>-->
<!--            <x-inline-chart title="7C" subtitle="استحلّ (staħall)"/>-->
<!--            <x-inline-chart title="7D" subtitle="استراح (starāħ)"/>-->
<!--        </div>-->
<!--        <div class="data-box" style="flex-basis: 45%">-->
<!--            <h2>FORM 8</h2>-->
<!--            <x-inline-chart title="8A" subtitle="اعتمل (ʕtamal)"/>-->
<!--            <x-inline-chart title="8B" subtitle="امتشى (mtaša)"/>-->
<!--            <x-inline-chart title="8C" subtitle="احتلّ (ħtall)"/>-->
<!--            <x-inline-chart title="8D" subtitle="ارتاح (rtāħ)"/>-->
<!--            <x-inline-chart title="8E" subtitle="اتّصل (ttaṣal)"/>-->
<!--        </div>-->

<!--    </div>-->

<!--    <p>Normally, the voweling of verbs is always entirely <b>/a/</b> in the <b>Past Tense</b> & <b>/i/</b> in the-->
<!--        <b>Present-->
<!--            Tense</b>. However, some <b>Form 1A & 1B</b> terms reverse that pattern. Additionally, multiple-->
<!--        vowelings-->
<!--        are possible for the <b>Present Tense</b>. Here are all the possible <b>Form 1</b> vowelings:</p>-->

<!--    <div class="data-box" style="width: 50%">-->
<!--        <h2>FORM 1</h2>-->
<!--        <x-inline-chart title="1A" subtitle="ترك (tarak) / يترك (yitrik)"/>-->
<!--        <x-inline-chart title="1Aa" subtitle="فتح (fataħ) / يفتح (yiftaħ)"/>-->
<!--        <x-inline-chart title="1Au" subtitle="كتب (katab) / يكتب (yuktub)"/>-->
<!--        <x-inline-chart title="RA" subtitle="فهم (fihim) / يفهم (yifham)"/>-->
<!--        <x-inline-chart title="RAi" subtitle="عرف (ʕirif) / يعرف (yiʕrif)"/>-->
<!--        <x-inline-chart title="1B" subtitle="مشى (maša) / يمشي (yimši)"/>-->
<!--        <x-inline-chart title="RB" subtitle="نسي (nisi) / ينسي (yinsa)"/>-->
<!--        <x-inline-chart title="1C" subtitle="حلّ (ħall) / يحلّ (yiħill)"/>-->
<!--        <x-inline-chart title="1Cu" subtitle="حطّ (ħaṭṭ) / يحطّ (yiħuṭṭ)"/>-->
<!--        <x-inline-chart title="1D" subtitle="صار (ṣār) / يصير (yṣīr)"/>-->
<!--        <x-inline-chart title="1Du" subtitle="راح (rāħ) / يروح (yrūħ)"/>-->
<!--    </div>-->

<!--    <div class="data-box" style="width: 50%">-->
<!--        <h2>FORM 1 (RARE)</h2>-->
<!--        <x-inline-chart title="1Ba" subtitle="قرا (ʔara) / يقرا (yiʔra)"/>-->
<!--        <x-inline-chart title="1Ca" subtitle="ضلّ (ḍall) / يضلّ (yḍall)"/>-->
<!--        <x-inline-chart title="1Da" subtitle="نام (nām) / ينام (ynām)"/>-->
<!--    </div>-->

<!--</div>-->
