<script setup>
import {ref, watch} from 'vue';

const props = defineProps({
    options: {type: Array, default: () => []},
    value: {type: String, default: ''},
    disabled: {type: Boolean, default: false},
    inputId: {type: String, required: false}
});

const emit = defineEmits(['input']);

const selectedValue = ref(props.value);

// Function to select an option
const selectOption = (option) => {
    if (!props.disabled) {
        selectedValue.value = option.data;
        emit('input', option.data);
    }
};

// Compute the class for buttons (indicating the active/selected state)
const buttonClass = (option) => {
    return {
        'button-option': true,
        'selected': option.data === selectedValue.value
    };
};

// Watch for changes to the value prop to sync the selected value
watch(
    () => props.value,
    (newVal) => {
        selectedValue.value = newVal;
    }
);
</script>

<template>
    <div class="button-select">
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
.button-select {
    display: flex;
}

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
