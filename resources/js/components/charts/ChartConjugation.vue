<script setup>
import {ref} from "vue";
import {conjugate} from "../../utils/Conjugator.js";
import ConjugationChart from "./ConjugationChart.vue";

const props = defineProps({
    roots: {type: Array, required: true},
    patterns: {type: Array, required: true},
});

const activeIndex = ref(0);
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
            </div>
        </template>
    </div>
</template>
