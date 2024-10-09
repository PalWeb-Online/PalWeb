<script setup>
import {computed, watch} from 'vue';

const props = defineProps({
    value: {type: Number, default: 0},
    placeholder: {type: String, default: ''},
    disabled: {type: Boolean, default: false},
    inputId: {type: String, required: false},
    label: {type: String, default: ''},
    min: {type: Number, required: false},
    max: {type: Number, required: false},
    step: {type: Number, required: false},
    percentage: {type: Boolean, default: false}
});

const emit = defineEmits(['input']);

// Computed value for handling percentage if necessary
const computedValue = computed(() => {
    return props.percentage ? (props.value * 100).toFixed(2) : props.value;
});

const handleInput = (event) => {
    let value = parseFloat(event.target.value);
    if (props.percentage) {
        value /= 100;
    }
    emit('input', value);
};

// Watch for external changes to value to update the input
watch(() => props.value, (newVal) => {
    const inputElement = document.getElementById(props.inputId);
    if (inputElement) {
        inputElement.value = props.percentage ? (newVal * 100).toFixed(2) : newVal;
    }
});
</script>

<template>
    <div>
        <label v-if="label" :for="inputId">{{ label }}</label>
        <input
            type="number"
            :id="inputId"
            :placeholder="placeholder"
            :disabled="disabled"
            :min="min"
            :max="max"
            :step="step"
            :value="computedValue"
            @input="handleInput"
        />
    </div>
</template>

<style scoped>
input[type="number"] {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}
</style>
