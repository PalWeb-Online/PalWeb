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
    <div class="block-editor--chart">
        <div class="field-item">
            <label>Title</label>
            <input v-model="block.title" placeholder="Title"/>
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

        <Draggable v-if="block.rows.length" :list="block.rows" item-key="id" handle=".handle" class="chart-editor-grid">
            <template #item="{ element: row, index: ri }">
                <div class="chart-row-wrapper">
                    <span class="handle material-symbols-rounded">drag_indicator</span>
                    <div class="chart-row-items">
                        <div v-for="(item, ii) in row.items" :key="ii" class="item-wrapper">
                            <div class="item-header">
                                <input v-model="item.key" :class="{ 'invalid': !item.key }"
                                       placeholder="Key"/>

                                <button v-if="row.items.length > 1" class="material-symbols-rounded"
                                        @click="removeItem(row, ii)">delete
                                </button>
                                <button v-else class="material-symbols-rounded"
                                        @click="addItemToRow(row)"
                                        title="Add Column">add
                                </button>
                            </div>

                            <div class="item-inputs">
                                <input v-model="item.ar" :class="{ 'invalid': !item.ar }" placeholder="Arabic"/>
                                <input v-model="item.tr" :class="{ 'invalid': !item.tr }" placeholder="Transcription"/>
                            </div>
                        </div>
                    </div>
                    <button class="material-symbols-rounded" @click="removeRow(ri)">delete</button>
                </div>
            </template>
        </Draggable>
    </div>
</template>

<style scoped lang="scss">
.block-editor--chart {
    display: grid;
    gap: 1.6rem;
}

.chart-editor-grid {
    display: grid;
    gap: 1.6rem;
    direction: rtl;
}

.chart-row-wrapper {
    display: flex;
    align-items: center;
    gap: 1.6rem;

    span, button {
        color: var(--color-medium-primary);
    }
}

.chart-row-items {
    flex-grow: 1;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.8rem;
}

.item-wrapper {
    display: grid;
    gap: 0.8rem;
}

.item-header {
    display: flex;
    justify-content: space-between;

    button {
        font-size: 2.0rem;
        color: var(--color-medium-primary);
    }

    input {
        font-weight: bold;
        width: 6.4rem;
    }
}

.item-inputs {
    display: flex;
    gap: 0.4rem;

    input {
        flex: 1;
    }
}
</style>
