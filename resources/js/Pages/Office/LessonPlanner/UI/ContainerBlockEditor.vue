<script setup>
import DocumentBlocksManager from "./DocumentBlocksManager.vue";

const props = defineProps({
    block: {type: Object, required: true},
});
</script>

<template>
    <div class="block-editor--container">
        <div class="field-item">
            <label>Title</label>
            <div class="field-input">
                <input type="text" v-model="block.title" placeholder="Title">
            </div>
        </div>

        <DocumentBlocksManager
            :document-blocks="block.blocks"
            :block-types="['text', 'chart', 'sentence']"
            :is-nested="true"
        />
    </div>
</template>

<style scoped>
</style>
