<script setup>
import AppTip from "../../../../components/AppTip.vue";
import {useDocumentBuilder} from "../../../../composables/useDocumentBuilder.js";

const props = defineProps({
    documentBlocks: {type: Array, required: true},
    blockTypes: {type: Array, required: true},
    isNested: {type: Boolean, default: false}
});

const builder = useDocumentBuilder(props.documentBlocks);

if (!props.isNested) {
    builder.provideBuilder();
}

const handleRemoveBlock = (documentBlocks, blockId) => {
    if (!confirm('Are you sure you want to remove this Block?')) return;
    removeBlock(documentBlocks, blockId);
}

const { addBlock, removeBlock, moveBlock, getBlockEditor } = useDocumentBuilder(props.documentBlocks);
</script>

<template>
    <div class="block-add-buttons">
        <div v-for="blockType in blockTypes">
            <div class="add-button"
                 @click="addBlock(documentBlocks, { type: blockType, atStart: true })">+
            </div>
            <div>{{ blockType }}</div>
        </div>
    </div>

    <AppTip v-if="documentBlocks.length === 0">
        <p>No Blocks have been added yet.</p>
    </AppTip>

    <div class="block-editor-container-wrapper" v-for="(block, bi) in documentBlocks" :key="block.id">
        <div class="block-editor-container" :class="{ nested: isNested }">
            <div class="block-meta">
                <div class="featured-title s" style="flex-grow: 1">
                    {{ bi + 1 }}:
                    <span style="color: var(--color-dark-primary)">
                                {{ block.type }}{{ block.exerciseType ? ': ' + block.exerciseType : '' }}
                            </span>
                </div>
                <button type="button"
                        class="material-symbols-rounded"
                        @click="moveBlock(documentBlocks, block.id, 'up')"
                        :disabled="bi === 0">
                    move_up
                </button>
                <button type="button"
                        class="material-symbols-rounded"
                        @click="moveBlock(documentBlocks, block.id, 'down')"
                        :disabled="bi === documentBlocks.length - 1">
                    move_down
                </button>
                <button type="button"
                        class="material-symbols-rounded"
                        style="margin-inline-start: 0.8rem"
                        @click="handleRemoveBlock(documentBlocks, block.id)">
                    delete
                </button>
            </div>
            <component
                :is="getBlockEditor(block.type)"
                :block="block"
            />
        </div>
        <div class="block-add-buttons">
            <span>Insert --></span>
            <div v-for="blockType in blockTypes">
                <div class="add-button"
                     @click="addBlock(documentBlocks, { afterBlockId: block.id, type: blockType })">+
                </div>
                <div>{{ blockType }}</div>
            </div>
        </div>
    </div>
</template>
