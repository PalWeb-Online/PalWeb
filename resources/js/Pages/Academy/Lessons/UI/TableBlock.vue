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
        <div class="block--table-column-label" v-for="column in table.columns" :key="column.id">{{ column.label }}</div>
        <template v-for="row in table.rows">
            <div class="block--table-row-cell" v-for="column in table.columns" :key="column.id">
                {{ row.cells[column.id] }}
            </div>
        </template>
    </div>
</template>
