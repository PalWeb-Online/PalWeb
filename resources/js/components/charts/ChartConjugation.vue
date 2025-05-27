<script setup>
import {computed, ref} from "vue";
import {conjugate} from "../../utils/Conjugator.js";
import ConjugationChart from "./ConjugationChart.vue";
import {route} from "ziggy-js";

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
    <div class="inflection-carousel">
        <template v-for="(pattern, index) in patterns">
            <div v-for="(root, i) in roots" v-show="activeIndex === index * roots.length + i" class="carousel-item">
                <div class="carousel-item-head">
                    <button v-if="patterns.length * roots.length > 1"
                            @click="activeIndex = (activeIndex > 0) ? activeIndex - 1 : (patterns.length * roots.length) - 1">
                        &larr;
                    </button>

                    <div style="flex-grow: 1">
                        {{ root['dialect'] }}
                        {{ pattern.form === '1' ? pattern.pattern : pattern.form + pattern.pattern }}
                    </div>

                    <button v-if="patterns.length * roots.length > 1"
                            @click="activeIndex = (activeIndex < (patterns.length * roots.length) - 1) ? activeIndex + 1 : 0">
                        &rarr;
                    </button>
                </div>

                <ConjugationChart :conjugation="conjugate(root, pattern)"/>

                <div v-if="derivatives.length > 0" class="inflection-chart-wrapper">
                    <div class="inflection-chart">
                        <div class="inflection-chart-title" style="grid-column: span 2">Derivatives</div>
                        <Link v-if="ap" :href="route('terms.show', ap.slug)" class="inflection-chart-item">
                            <div>AP</div>
                            <div>
                                <div>{{ ap.term }}</div>
                                <div>{{ ap.translit }}</div>
                            </div>
                        </Link>
                        <Link v-if="pp" :href="route('terms.show', pp.slug)" class="inflection-chart-item">
                            <div>PP</div>
                            <div>
                                <div>{{ pp.term }}</div>
                                <div>{{ pp.translit }}</div>
                            </div>
                        </Link>
                        <Link v-if="vn" :href="route('terms.show', vn.slug)" class="inflection-chart-item">
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
