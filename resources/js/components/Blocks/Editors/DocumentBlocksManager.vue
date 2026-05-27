<script setup>
import AppTip from "../../AppTip.vue";
import {useDocumentBuilder} from "../../../composables/useDocumentBuilder.js";
import {computed, reactive} from "vue";

const props = defineProps({
    documentBlocks: {type: Array, required: true},
    blockTypes: {type: Array, default: null},
    isNested: {type: Boolean, default: false}
});

const {
    inheritedContext,
    provideBuilder,
    addBlock,
    removeBlock,
    moveBlock,
    getBlockEditor,
} = useDocumentBuilder(props.documentBlocks);

const collapsedBlockIds = reactive(new Set());

const isBlockOpen = (blockId) => {
    return !collapsedBlockIds.has(blockId);
};

const toggleBlock = (blockId) => {
    if (collapsedBlockIds.has(blockId)) {
        collapsedBlockIds.delete(blockId);
        return;
    }

    collapsedBlockIds.add(blockId);
};

const getExerciseCount = (block) => {
    if (block?.type !== 'exercises') {
        return 0;
    }

    const items = Array.isArray(block.items) ? block.items : [];

    if (block.exerciseType === 'match') {
        return items.reduce((total, item) => {
            return total + (Array.isArray(item.pairs) ? item.pairs.length : 0);
        }, 0);
    }

    return items.length;
};

const getTotalExerciseCount = (blocks) => {
    if (!Array.isArray(blocks)) {
        return 0;
    }

    return blocks.reduce((total, block) => {
        const nestedBlocks = Array.isArray(block?.blocks) ? block.blocks : [];

        return total
            + getExerciseCount(block)
            + getTotalExerciseCount(nestedBlocks);
    }, 0);
};

const totalExerciseCount = computed(() => {
    return getTotalExerciseCount(props.documentBlocks);
});

const resolvedBlockTypes = computed(() => {
    return props.blockTypes
        ?? inheritedContext?.blockTypes?.value
        ?? inheritedContext?.blockTypes
        ?? [];
});

if (!props.isNested) {
    provideBuilder({
        blockTypes: resolvedBlockTypes,
    });
}

const handleRemoveBlock = (documentBlocks, blockId) => {
    if (!confirm('Are you sure you want to remove this Block?')) return;
    removeBlock(documentBlocks, blockId);
}

const handleFlattenContainer = (containerId) => {
    const arr = props.documentBlocks;

    const index = arr.findIndex(b => b?.id === containerId);
    if (index === -1) return;

    const container = arr[index];
    const children = Array.isArray(container?.blocks) ? container.blocks : [];

    arr.splice(index, 1);

    if (children.length) {
        arr.splice(index, 0, ...children);
    }

    container.blocks = [];
};
</script>

<template>
    <div class="block-add-buttons">
        <div v-for="blockType in resolvedBlockTypes">
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
                    <span>{{ bi + 1 }}: </span>
                    <span style="color: var(--color-dark-primary)">
                        {{ block.type }}
                    </span>
                    <template v-if="block.type === 'exercises'">
                        {{ block.exerciseType ? ': ' + block.exerciseType : '' }}
                        <span style="color: var(--color-medium-secondary)">
                            {{ getExerciseCount(block) }}
                        </span>
                    </template>
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
                <button type="button"
                        class="material-symbols-rounded"
                        style="margin-inline-start: 0.8rem"
                        @click="toggleBlock(block.id)">
                    {{ isBlockOpen(block.id) ? 'expand_less' : 'expand_more' }}
                </button>
            </div>
            <component v-show="isBlockOpen(block.id)"
                       :is="getBlockEditor(block.type)"
                       :block="block"
                       @flatten="handleFlattenContainer"
            />
        </div>
        <div class="block-add-buttons">
            <span>Insert --></span>
            <div v-for="blockType in resolvedBlockTypes">
                <div class="add-button"
                     @click="addBlock(documentBlocks, { afterBlockId: block.id, type: blockType })">+
                </div>
                <div>{{ blockType }}</div>
            </div>
        </div>
    </div>

    <div v-if="!isNested && documentBlocks.some(block => block.type === 'exercises')" class="featured-title s" style="margin-block-start: 1rem">
        Total Exercises:
        <span style="color: var(--color-dark-primary)">
            {{ totalExerciseCount }}
        </span>
    </div>
</template>
