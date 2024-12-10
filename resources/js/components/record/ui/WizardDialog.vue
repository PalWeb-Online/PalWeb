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
    return `dialog-${props.size}`;
});

defineExpose({
    openDialog,
    closeDialog,
});
</script>

<template>
    <div class="rw-dialog-wrapper">
        <div class="rw-dialog-trigger" @click="openDialog">
            <slot name="trigger"></slot>
        </div>

        <div v-if="isDialogVisible" class="rw-dialog-overlay" @click.self="closeDialog">
            <div class="rw-dialog" :class="sizeClass">
                <div class="rw-dialog-tab">
                    <div class="rw-dialog-title">
                        {{ title }}
                    </div>
                    <img class="rw-dialog-close"
                         @click="closeDialog"
                         alt="Close"
                         src="/img/close.svg"/>
                </div>
                <div class="rw-dialog-body">
                    <slot name="content"></slot>
                </div>
            </div>
        </div>
    </div>
</template>
