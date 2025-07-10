<script setup>
import {computed} from "vue";

const props = defineProps({
    term: {type: Object, required: true},
    inflections: {type: Array, required: true},
});

const type = computed(() => {
    let resultType = 'singular';

    if (props.term.attributes.some(attribute => attribute.attribute === "collective")) {
        resultType = "collective";
    }

    if (props.inflections.some(inflection => inflection.form === "fem")) {
        resultType = "masculine";
    }

    return resultType;
});

const singulative = computed(() =>
    props.inflections.find(inflection => inflection.form === "sing")
);

const paucal = computed(() =>
    props.inflections.find(inflection => inflection.form === "pauc")
);

const feminine = computed(() =>
    props.inflections.find(inflection => inflection.form === "fem")
);

const elative = computed(() =>
    props.inflections.find(inflection => inflection.form === "elt")
);

const plurals = computed(() =>
    props.inflections.filter(inflection => inflection.form === "plr")
);

</script>

<template>
    <!--    todo: Inflections may have Audios-->
    <div class="window-section-head">
        <h2>inflections</h2>
    </div>
    <div class="inflection-carousel">
        <div class="carousel-item">
            <div class="inflection-chart-wrapper">
                <div class="inflection-chart">
                    <template v-if="type === 'collective'">
                        <div class="inflection-chart-item" style="grid-column: span 2">
                            <div>C</div>
                            <div>
                                <div>{{ term.term }}</div>
                                <div>{{ term.translit }}</div>
                            </div>
                        </div>
                        <div v-if="singulative" class="inflection-chart-item">
                            <div>S</div>
                            <div>
                                <div>{{ singulative.inflection }}</div>
                                <div>{{ singulative.translit }}</div>
                            </div>
                        </div>
                        <div v-if="paucal" class="inflection-chart-item">
                            <div>P</div>
                            <div>
                                <div>{{ paucal.inflection }}</div>
                                <div>{{ paucal.translit }}</div>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div
                            class="inflection-chart-item"
                            :style="{
                                gridColumn: feminine
                                    ? 'span 1'
                                    : 'span ' + (plurals.length * 2),
                            }"
                        >
                            <div>{{ type === 'singular' ? 'S' : 'M' }}</div>
                            <div>
                                <div>{{ term.term }}</div>
                                <div>{{ term.translit }}</div>
                            </div>
                        </div>
                        <div v-if="feminine" class="inflection-chart-item">
                            <div>F</div>
                            <div>
                                <div>{{ feminine.inflection }}</div>
                                <div>{{ feminine.translit }}</div>
                            </div>
                        </div>
                        <div v-for="plural in plurals" class="inflection-chart-item" style="grid-column: span 2">
                            <div>P</div>
                            <div>
                                <div>{{ plural.inflection }}</div>
                                <div>{{ plural.translit }}</div>
                            </div>
                        </div>
                        <div v-if="elative" class="inflection-chart-item" style="grid-column: span 2">
                            <div>E</div>
                            <div>
                                <div>{{ elative.inflection }}</div>
                                <div>{{ elative.translit }}</div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
