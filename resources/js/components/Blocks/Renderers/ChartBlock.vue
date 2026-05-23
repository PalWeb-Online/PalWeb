<script setup>
import {computed} from "vue";

const props = defineProps({
    block: {type: Object, required: true}
})

const maxCols = computed(() => {
    return Math.max(...props.block.rows?.map(row => row.items.length), 1);
});

const gridStyle = computed(() => ({
    gridTemplateColumns: `repeat(${maxCols.value}, 1fr)`,
}));

const getItemSpan = (rowLength) => {
    if (rowLength === 1 && maxCols.value === 2) {
        return 'span 2';
    }

    return 'span 1';
};
</script>
<template>
    <div class="inflection-carousel">
        <div class="carousel-item">
            <div v-if="block.title" class="window-section-head">
                <h3>{{ block.title }}</h3>
            </div>
            <div class="inflection-chart-wrapper">
                <div class="inflection-chart" :style="gridStyle">
                    <template v-for="row in block.rows" :key="row.id">
                        <div v-for="item in row.items" :key="item.key"
                             class="inflection-chart-item"
                             :style="{ gridColumn: getItemSpan(row.items.length) }"
                        >
                            <div>{{ item.key }}</div>
                            <div>
                                <div>{{ item.ar }}</div>
                                <div>{{ item.tr }}</div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
