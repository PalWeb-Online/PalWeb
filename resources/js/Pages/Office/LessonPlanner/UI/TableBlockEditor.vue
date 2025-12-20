<script setup>
import AppTip from "../../../../components/AppTip.vue";
import {computed} from "vue";
import Draggable from "vuedraggable";
import {useDocumentBuilder} from "../../../../composables/useDocumentBuilder.js";

const { addTableColumn, removeTableColumn, addTableRow, removeTableRow } = useDocumentBuilder();

const props = defineProps({
    block: {type: Object, required: true},
});

const gridStyle = computed(() => ({
    gridTemplateColumns: `repeat(${props.block.columns?.length ?? 1}, 1fr)`,
}));
</script>

<template>
    <div class="block--table">
        <div class="block-add-buttons">
            <div>
                <div class="add-button"
                     @click="addTableColumn({ blockId: props.block.id })">
                    +
                </div>
                <div>column</div>
            </div>
            <div>
                <div class="add-button" @click="addTableRow({ blockId: props.block.id })">
                    +
                </div>
                <div>row</div>
            </div>
        </div>

        <AppTip v-if="(props.block.columns?.length ?? 0) === 0">
            <p>Add at least one column to start entering row data.</p>
        </AppTip>

        <Draggable v-if="props.block.columns?.length" class="table-grid" :style="gridStyle"
                   :list="props.block.columns"
                   itemKey="id"
                   handle=".handle"
        >
            <template #item="{ element: col }">
                <div class="table-column-wrapper" style="position:relative;">
                    <button type="button" class="material-symbols-rounded"
                            @click="removeTableColumn({ blockId: props.block.id, columnId: col.id })"
                    >
                        delete
                    </button>
                    <input v-model="col.label" :class="{ 'invalid': !col.label }" style="width: 100%"
                           placeholder="عمود"/>
                    <span class="handle material-symbols-rounded" style="cursor:grab;">menu</span>
                </div>
            </template>
        </Draggable>

        <Draggable v-if="props.block.rows?.length" class="table-rows"
                   :list="props.block.rows"
                   itemKey="id"
                   handle=".handle"
        >
            <template #item="{ element: row }">
                <div class="table-row-wrapper">
                    <div class="block-meta" style="justify-content: flex-end">
                        <span class="handle material-symbols-rounded" style="cursor:grab;">menu</span>
                        <button
                            type="button"
                            class="material-symbols-rounded"
                            @click="removeTableRow({ blockId: props.block.id, rowId: row.id })"
                        >
                            delete
                        </button>
                    </div>
                    <div class="table-grid" :style="gridStyle">
                        <div class="table-row-content"
                             v-for="col in props.block.columns" :key="col.id">
                            <input v-model="row.cells[col.id]" :class="{ 'invalid': !row.cells[col.id] }"
                                   style="width: 100%;"
                                   placeholder="خليّة"
                            />
                        </div>
                    </div>
                </div>
            </template>
        </Draggable>
    </div>
</template>
