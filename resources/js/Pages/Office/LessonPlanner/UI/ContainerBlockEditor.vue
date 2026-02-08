<script setup>
import DocumentBlocksManager from "./DocumentBlocksManager.vue";

const props = defineProps({
    block: {type: Object, required: true},
});

const emit = defineEmits(['flatten']);

const flattenContainer = () => {
    if (!props.block?.blocks?.length) return;
    if (!confirm('Flatten this Container? Its nested blocks will be moved up one level and the Container will be removed.')) return;

    emit('flatten', props.block.id);
};
</script>

<template>
    <div class="block-editor--container">
        <div class="field-item">
            <input type="text" v-model="block.title" placeholder="Title">
        </div>

        <DocumentBlocksManager
            :document-blocks="block.blocks"
            :block-types="['text', 'chart', 'sentence']"
            :is-nested="true"
        />

        <button @click="flattenContainer" :disabled="(block.blocks?.length ?? 0) === 0">
            flatten
        </button>
    </div>
</template>

<style scoped>
</style>
