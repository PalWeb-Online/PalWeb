<script setup>
import {computed, ref} from 'vue';

const props = defineProps({
    name: { type: String, default: '' },
    size: { type: String, default: 'medium' } // Sizes could be: 'small', 'medium', 'large'
});

const emit = defineEmits(['open', 'close']);

const isDialogVisible = ref(false);

// Open the dialog
const openDialog = () => {
    isDialogVisible.value = true;
    emit('open');
};

// Close the dialog
const closeDialog = () => {
    isDialogVisible.value = false;
    emit('close');
};

// Dynamically set the dialog size class
const sizeClass = computed(() => {
    return `dialog-${props.size}`;
});
</script>

<template>
    <div>
        <!-- Trigger for opening the dialog -->
        <span class="wizard-dialog-trigger" @click="openDialog">
      <slot name="trigger"></slot>
    </span>

        <!-- Dialog content -->
        <div v-if="isDialogVisible" class="wizard-dialog-overlay" @click.self="closeDialog">
            <div class="wizard-dialog" :class="sizeClass">
                <button class="close-button" @click="closeDialog">X</button>
                <slot name="content"></slot>
            </div>
        </div>
    </div>
</template>

<style scoped>
.wizard-dialog-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.wizard-dialog {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    position: relative;
    max-width: 80%;
}

.dialog-small {
    width: 300px;
}

.dialog-medium {
    width: 500px;
}

.dialog-large {
    width: 800px;
}

.close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    background: transparent;
    border: none;
    font-size: 20px;
    cursor: pointer;
}
</style>
