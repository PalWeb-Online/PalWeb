<script setup>
import {ref, watch, onMounted, onBeforeUnmount} from 'vue';

const props = defineProps({
    value: { type: Boolean, default: false },
    width: { type: Number, default: 300 },
    align: { type: String, default: 'backwards' },
    label: { type: String, default: '' },
    head: { type: Boolean, default: false },
    autoClose: { type: Boolean, default: true },
    anchor: { type: Boolean, default: true }
});

const emit = defineEmits(['input']);

const isVisible = ref(props.value);

// Method to toggle popup visibility
const toggle = () => {
    isVisible.value = !isVisible.value;
    emit('input', isVisible.value);
};

// Watch for external changes to `value`
watch(() => props.value, (newValue) => {
    isVisible.value = newValue;
});

// Auto-close functionality
const handleClickOutside = (event) => {
    if (props.autoClose && !event.target.closest('.wizard-popup-content')) {
        isVisible.value = false;
        emit('input', isVisible.value);
    }
};

// Set up event listener for auto-closing the popup
onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

// Clean up the event listener when component is destroyed
onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div class="wizard-popup">
        <!-- Trigger for opening the popup -->
        <span class="wizard-popup-trigger" @click="toggle">
      <slot name="trigger"></slot>
    </span>

        <!-- Popup content -->
        <div v-if="isVisible" class="wizard-popup-content" :style="{ width: `${width}px` }">
            <slot name="content"></slot>
        </div>
    </div>
</template>

<style scoped>
.wizard-popup {
    position: relative;
}

.wizard-popup-trigger {
    cursor: pointer;
}

.wizard-popup-content {
    position: absolute;
    background-color: white;
    border: 1px solid #ccc;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 10px;
    z-index: 100;
}
</style>
