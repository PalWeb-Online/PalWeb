<script setup>
import DocumentBlocksManager from "./DocumentBlocksManager.vue";
import {useI18n} from "vue-i18n";

const {t} = useI18n();

const props = defineProps({
    block: {type: Object, required: true},
});

const emit = defineEmits(['flatten']);

const flattenContainer = () => {
    if (!props.block?.blocks?.length) return;
    if (!confirm(t('block.notifications.flatten-container'))) return;

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
            :is-nested="true"
        />

        <button @click="flattenContainer" :disabled="(block.blocks?.length ?? 0) === 0">
            {{ $t('block.buttons.flatten') }}
        </button>
    </div>
</template>
