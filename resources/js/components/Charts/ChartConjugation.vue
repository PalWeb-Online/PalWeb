<script setup>
import {computed, ref} from "vue";
import {conjugate} from "../../utils/Conjugator.js";
import ConjugationChart from "./ConjugationChart.vue";
import {route} from "ziggy-js";
import AppTip from "../AppTip.vue";

const props = defineProps({
    roots: {type: Array, required: true},
    patterns: {type: Array, required: true},
    derivatives: Array,
});

const activeIndex = ref(0);

const ap = computed(() =>
    props.derivatives.find(relative => relative.type === "ap")
);

const pp = computed(() =>
    props.derivatives.find(relative => relative.type === "pp")
);

const vn = computed(() =>
    props.derivatives.find(relative => relative.type === "vn")
);
</script>

<template>
    <div class="window-section-head">
        <h2>conjugation</h2>
    </div>
    <div class="inflection-carousel">
        <template v-for="(pattern, index) in patterns">
            <div v-for="(root, i) in roots" v-show="activeIndex === index * roots.length + i" class="carousel-item">
                <div class="window-section-head">
                    <button v-if="patterns.length * roots.length > 1" class="material-symbols-rounded"
                            @click="activeIndex = (activeIndex > 0) ? activeIndex - 1 : (patterns.length * roots.length) - 1">
                        arrow_back
                    </button>
                    <h3>
                        {{ root['dialect'] }}
                        {{ pattern.form === '1' ? pattern.pattern : pattern.form + pattern.pattern }}
                    </h3>
                    <button v-if="patterns.length * roots.length > 1" class="material-symbols-rounded"
                            @click="activeIndex = (activeIndex < (patterns.length * roots.length) - 1) ? activeIndex + 1 : 0">
                        arrow_forward
                    </button>
                </div>
                <AppTip v-if="['و', 'ي'].includes(root.ar[0]) && ['A1', 'A2i', 'A1a', 'A2'].includes(pattern.pattern)">
                    <p>Because the first root letter is a consonant-vowel (i.e. <b>و</b> or <b>ي</b>), the coda of the
                        first syllable in the Present Tense results in a <b>"iw"</b> or <b>"iy"</b> that is elided to
                        <b>"ū"</b> or <b>"ī"</b>, respectively.</p>
                </AppTip>

                <ConjugationChart :conjugation="conjugate(root, pattern)"/>

                <div v-if="derivatives.length > 0" class="inflection-chart-wrapper">
                    <div class="inflection-chart">
                        <div class="inflection-chart-title" style="grid-column: span 2">Derivatives</div>
                        <Link v-if="ap" :href="route('terms.show', ap.slug)" class="inflection-chart-item" style="grid-column: 1">
                            <div>AP</div>
                            <div>
                                <div>{{ ap.term }}</div>
                                <div>{{ ap.translit }}</div>
                            </div>
                        </Link>
                        <Link v-if="pp" :href="route('terms.show', pp.slug)" class="inflection-chart-item" style="grid-column: 2">
                            <div>PP</div>
                            <div>
                                <div>{{ pp.term }}</div>
                                <div>{{ pp.translit }}</div>
                            </div>
                        </Link>
                        <Link v-if="vn" :href="route('terms.show', vn.slug)" class="inflection-chart-item" style="grid-column: span 2">
                            <div>VN</div>
                            <div>
                                <div>{{ vn.term }}</div>
                                <div>{{ vn.translit }}</div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>
