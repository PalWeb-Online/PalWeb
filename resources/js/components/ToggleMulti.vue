<script setup>
const props = defineProps({
    modelValue: {
        type: String,
        required: true
    },
    label: {
        type: String,
        default: ''
    },
    options: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['update:modelValue', 'change']);

function handleSelect(option) {
    emit('update:modelValue', option)
    emit('change', option);
}
</script>

<template>
    <div class="toggle-multi-wrapper">
        <div class="toggle-multi-label">{{ label }}</div>
        <div style="display: flex; gap: 2.4rem; align-items: center; padding: 0.8rem">
            <div class="toggle-option-wrapper" v-for="option in options"
                 @click="handleSelect(option)"
            >
                <button :class="{active: modelValue === option}"></button>
                <div class="toggle-option-label">{{ option }}</div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.toggle-multi-wrapper {
    display: grid;
    gap: 0.8rem;
    justify-items: center;
    background: var(--color-pastel-light);
    padding: 1.2rem 1.6rem;
    border-radius: 0.8rem;

    .toggle-multi-label {
        font-size: 1.6rem;
    }
}

.toggle-option-wrapper {
    display: flex;
    gap: 0.8rem;
    align-items: center;
    font-size: 1.6rem;

    button {
        width: 1.5em;
        height: 1.5em;
        background: var(--color-pastel-dark);
        border-radius: 50%;
        cursor: pointer;
    }

    button.active {
        background: var(--color-dark-primary);
    }

    .toggle-option-label {
    }
}

.toggle-multi-label, .toggle-option-label {
    color: var(--color-dark-primary);
    font-family: var(--mono-font), monospace;
    font-weight: 700;
    font-size: 1.4rem;
    text-transform: uppercase;
}
</style>
