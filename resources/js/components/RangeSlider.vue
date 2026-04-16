<script setup>
const props = defineProps({
    modelValue: {
        type: [Number, String],
        required: true
    },
    label: {
        type: String,
        default: ''
    },
    min: {
        type: [Number, String],
        default: 0
    },
    max: {
        type: [Number, String],
        default: 100
    },
    step: {
        type: [Number, String],
        default: 1
    }
});
const emit = defineEmits(['update:modelValue', 'change']);

function updateValue(event) {
    const value = Number(event.target.value);
    emit('update:modelValue', value);
}

function handleChange(event) {
    emit('change', Number(event.target.value));
}
</script>

<template>
    <div class="setting-item">
        <label>{{ label }}: {{ modelValue }}</label>
        <input
            type="range"
            :min="min"
            :max="max"
            :step="step"
            :value="modelValue"
            @input="updateValue"
            @change="handleChange"
        >
    </div>
</template>

<style scoped lang="scss">
.setting-item {
    width: 100%;
    display: grid;
    background: var(--color-pastel-light);
    border-radius: 0.8rem;
    padding: 0.8rem 1.6rem;

    label {
        font-family: var(--mono-font), monospace;
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--color-dark-primary);
        justify-self: center;
        text-transform: uppercase;
    }

    input {
        flex-grow: 1;
    }
}
</style>
