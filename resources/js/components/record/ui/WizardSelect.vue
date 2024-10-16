<script setup>
import {ref, watch} from 'vue';

const props = defineProps({
    options: {type: Array, default: () => []},
    modelValue: {type: String, default: ''}, // Use modelValue for v-model binding
    disabled: {type: Boolean, default: false},
    inputId: {type: String, required: false}
});

const emit = defineEmits(['update:modelValue']);

const selectedValue = ref(props.modelValue);

// Function to select an option
const selectOption = (option) => {
    if (!props.disabled) {
        selectedValue.value = option.data;
        emit('update:modelValue', option.data); // Emit the correct event
    }
};

// Compute the class for buttons (indicating the active/selected state)
const buttonClass = (option) => {
    return {
        'wizard-button': true,
        'selected': option.data === selectedValue.value
    };
};

// Watch for changes to the modelValue prop to sync the selected value
watch(
    () => props.modelValue,
    (newVal) => {
        selectedValue.value = newVal;
    }
);
</script>

<template>
    <div class="wizard-select">
        <button
            v-for="option in options"
            :key="option.data"
            :class="buttonClass(option)"
            :disabled="disabled"
            @click="selectOption(option)"
        >
            {{ option.label }}
        </button>
    </div>
</template>

<style scoped>

.button-option {
    margin: 0 5px;
    padding: 8px 16px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.button-option.selected {
    background-color: #0056b3;
}

.button-option:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
