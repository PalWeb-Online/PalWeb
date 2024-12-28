<script setup>
import {computed, ref} from 'vue';

const props = defineProps({
    title: {type: String, default: ''},
    size: {type: String, default: 'medium'}
});

const emit = defineEmits(['open', 'close']);

const isDialogVisible = ref(false);

const openDialog = () => {
    isDialogVisible.value = true;
    emit('open');
};

const closeDialog = () => {
    isDialogVisible.value = false;
    emit('close');
};

const sizeClass = computed(() => {
    return `app-dialog-${props.size}`;
});

defineExpose({
    openDialog,
    closeDialog,
});
</script>

<template>
    <div class="app-dialog-trigger" @click="openDialog">
        <slot name="trigger"></slot>
    </div>

    <div v-if="isDialogVisible" class="app-dialog-overlay" @click.self="closeDialog">
        <div class="app-dialog" :class="sizeClass">
            <div class="app-dialog-tab">
                <div class="app-dialog-title">
                    {{ title }}
                </div>
                <img class="app-dialog-close"
                     @click="closeDialog"
                     alt="Close"
                     src="/img/close.svg"/>
            </div>
            <div class="app-dialog-body">
                <slot name="content"></slot>
            </div>
        </div>
    </div>
</template>
