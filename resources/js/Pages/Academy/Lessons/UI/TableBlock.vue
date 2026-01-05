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

<style scoped lang="scss">
.block--table {
    display: grid;
    direction: rtl;
    gap: 0.8rem;
    padding: 0.8rem;
    margin-block-end: 3.2rem;
    border-radius: 1.2rem;
    font-size: 2.0rem;
    font-weight: 700;
    box-shadow: 0 0.8rem 0 var(--color-accent-light);
    background: var(--color-polar-light);
    font-family: var(--mono-font), monospace;
    text-align: center;
    overflow: hidden;

    & > * {
        color: var(--color-dark-secondary);
        min-width: 12rem;
        padding: 1.2rem 3.6rem;
        border-radius: 0.8rem;
    }

    .block--table-column-label {
        background: var(--color-pastel-medium);
    }

    .block--table-row-cell {
        background: var(--color-pastel-light);
    }
}
</style>
