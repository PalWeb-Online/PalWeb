<script setup>
import Draggable from "vuedraggable";
import {useDocumentBuilder} from "../../../../composables/useDocumentBuilder.js";
import ChartBlock from "../../../Academy/Lessons/UI/ChartBlock.vue";

const props = defineProps({
    block: {type: Object, required: true},
});

const {uid, applyChartTemplate} = useDocumentBuilder();

const addRow = () => {
    props.block.rows.push({id: uid(), items: [{key: '', ar: '', tr: ''}]});
};

const addItemToRow = (row) => {
    if (row.items.length < 2) {
        row.items.push({key: '', ar: '', tr: ''});
    }
};

const removeRow = (index) => props.block.rows.splice(index, 1);
const removeItem = (row, index) => row.items.splice(index, 1);
</script>

<template>
    <div class="block--chart">
        <div class="field-item">
            <label>Chart Title</label>
            <input v-model="block.title" placeholder="e.g. Subject Pronouns"/>
        </div>

        <div class="block-add-buttons">
            <div>
                <div class="add-button" @click="applyChartTemplate(block, 'person')">+</div>
                <div>person</div>
            </div>
            <div>
                <div class="add-button" @click="applyChartTemplate(block, 'inflection')">+</div>
                <div>inflection</div>
            </div>
            <div>
                <div class="add-button" @click="addRow">+</div>
                <div>row</div>
            </div>
        </div>

        <ChartBlock :chart="block"/>

        <Draggable :list="block.rows" item-key="id" handle=".handle" class="chart-editor-grid">
            <template #item="{ element: row, index: ri }">
                <div class="chart-row-edit-wrapper">
                    <div class="row-controls">
                        <span class="handle material-symbols-rounded">drag_indicator</span>
                        <button class="material-symbols-rounded" @click="addItemToRow(row)" v-if="row.items.length < 2" title="Add Column">add</button>
                        <button class="material-symbols-rounded" @click="removeRow(ri)">delete</button>
                    </div>

                    <div class="chart-row-items" :class="{ 'single-col': row.items.length === 1 }">
                        <div v-for="(item, ii) in row.items" :key="ii" class="chart-item-edit">
                            <div class="item-header">
                                <input v-model="item.key" class="key-input" :class="{ 'invalid': !item.key }" placeholder="Key"/>
                                <button @click="removeItem(row, ii)" v-if="row.items.length > 1">×</button>
                            </div>
                            <div class="item-inputs">
                                <input v-model="item.ar" :class="{ 'invalid': !item.ar }" placeholder="Arabic"/>
                                <input v-model="item.tr" :class="{ 'invalid': !item.tr }" placeholder="Transcription"/>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </Draggable>
    </div>
</template>

<style scoped lang="scss">
.chart-editor-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 1rem;
}

.chart-row-items {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;

    &.single-col .chart-item-edit {
        grid-column: span 2;
    }
}

.chart-item-edit {
    background: var(--color-light-gray);
    padding: 0.5rem;
    border-radius: 4px;

    .item-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.3rem;

        .key-input {
            font-weight: bold;
            width: 60px;
        }
    }

    .item-inputs {
        display: flex;
        gap: 0.3rem;

        input {
            flex: 1;
        }
    }
}
</style>
