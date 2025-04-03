<script setup>
import {ref} from "vue";

defineProps({
    inflections: {type: Array, required: true},
});

const activeIndex = ref(0);

const genitiveResult = (inflection) => {
    if (inflection.form === 'genitive') {
        if (['ā', 'ō', 'ū'].includes(inflection.translit.slice(-1))) {
            return {ar: inflection.inflection + 'ي', tr: inflection.translit + 'y'};

        } else if (inflection.translit.endsWith('ē')) {
            return {ar: inflection.inflection + 'ّ', tr: inflection.translit.slice(0, -1) + 'ayy'};

        } else if (inflection.translit.endsWith('ī')) {
            return {ar: inflection.inflection + 'ّي', tr: inflection.translit.slice(0, -1) + 'iyyi'};

        } else {
            return {ar: inflection.inflection + 'ي', tr: inflection.translit + 'i'};
        }

    } else if (inflection.form === 'accusative') {
        return {ar: inflection.inflection + 'ني', tr: inflection.translit + 'ni'};
    }

    return null;
};
</script>

<template>
    <div class="inflection-carousel">
        <div v-for="(inflection, index) in inflections" v-show="activeIndex === index" class="carousel-item">
            <div v-if="inflections.length > 1" class="carousel-item-head">
                <button @click="activeIndex = (activeIndex > 0) ? activeIndex - 1 : inflections.length - 1">
                    &larr;
                </button>
                Variant {{ index + 1 }}
                <button @click="activeIndex = (activeIndex < inflections.length - 1) ? activeIndex + 1 : 0">
                    &rarr;
                </button>
            </div>

            <div class="inflection-chart-wrapper">
                <div class="inflection-chart">
                    <div class="inflection-chart-item">
                        <div>1S</div>
                        <div>
                            <div>{{ genitiveResult(inflection)?.ar }}</div>
                            <div>{{ genitiveResult(inflection)?.tr }}</div>
                        </div>
                    </div>
                    <div class="inflection-chart-item">
                        <div>1P</div>
                        <div>
                            <div>{{ inflection.inflection }}نا</div>
                            <div>{{ inflection.translit }}na</div>
                        </div>
                    </div>
                    <div class="inflection-chart-item">
                        <div>2M</div>
                        <div>
                            <div>{{ inflection.inflection }}ك</div>
                            <div>{{
                                    inflection.translit
                                }}{{
                                    !['ā', 'ē', 'ī', 'ō', 'ū'].includes(inflection.translit.slice(-1)) ? 'ak' : 'k'
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="inflection-chart-item">
                        <div>2F</div>
                        <div>
                            <div>{{
                                    inflection.inflection
                                }}{{
                                    !['ā', 'ē', 'ī', 'ō', 'ū'].includes(inflection.translit.slice(-1)) ? 'ك' : 'كي'
                                }}
                            </div>
                            <div>{{
                                    inflection.translit
                                }}{{
                                    !['ā', 'ē', 'ī', 'ō', 'ū'].includes(inflection.translit.slice(-1)) ? 'ik' : 'ki'
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="inflection-chart-item" style="grid-column: span 2">
                        <div>2P</div>
                        <div>
                            <div>{{ inflection.inflection }}كم</div>
                            <div>{{ inflection.translit }}kom</div>
                        </div>
                    </div>
                    <div class="inflection-chart-item">
                        <div>3M</div>
                        <div>
                            <div>{{ inflection.inflection }}ه</div>
                            <div>{{
                                    inflection.translit
                                }}{{
                                    !['ā', 'ē', 'ī', 'ō', 'ū'].includes(inflection.translit.slice(-1)) ? 'o' : '(h)'
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="inflection-chart-item">
                        <div>3F</div>
                        <div>
                            <div>{{ inflection.inflection }}ها</div>
                            <div>{{ inflection.translit }}ha</div>
                        </div>
                    </div>
                    <div class="inflection-chart-item" style="grid-column: span 2">
                        <div>3P</div>
                        <div>
                            <div>{{ inflection.inflection }}هم</div>
                            <div>{{ inflection.translit }}hom</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
