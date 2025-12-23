<script setup>
import {computed} from "vue";

const props = defineProps({
    table: {type: Object, required: true}
})

const gridStyle = computed(() => ({
    gridTemplateColumns: `repeat(${props.table.columns.length}, 1fr)`,
}));
</script>
<template>
    <div class="block--table" :style="gridStyle">
        <div class="block--table-column-label" v-for="column in table.columns">{{ column.label }}</div>
        <template v-for="row in table.rows">
            <div class="block--table-row-cell" v-for="cell in row.cells">{{ cell }}</div>
        </template>
    </div>
</template>
