<script setup>
const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true
    },
    label: {
        type: String,
        default: ''
    },
    optionA: {
        type: String,
        required: true
    },
    optionB: {
        type: String,
        required: true
    },
});

const emit = defineEmits(['update:modelValue']);
</script>

<template>
    <div class="toggle-double-wrapper">
        <div class="toggle-double-label">{{ label }}</div>
        <div class="toggle-button-wrapper">
            <div :class="!modelValue ? 'active' : ''">{{ optionA }}</div>
            <button
                type="button"
                class="toggle-button"
                :class="{ active: modelValue }"
                @click="emit('update:modelValue', !props.modelValue)"
            >
                <div class="toggle-button-slider"></div>
            </button>
            <div :class="modelValue ? 'active' : ''">{{ optionB }}</div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.toggle-double-wrapper {
    display: grid;
    gap: 0.4rem;
    justify-items: center;
    background: var(--color-pastel-light);
    padding: 0.8rem 1.6rem 1.2rem;
    border-radius: 0.8rem;

    .toggle-double-label {
        color: var(--color-dark-primary);
        font-family: var(--mono-font), monospace;
        font-weight: 700;
        font-size: 1.4rem;
        text-transform: uppercase;
    }

    .toggle-button-wrapper > div {
        font-size: 1.2rem;
        text-transform: uppercase;
        color: var(--color-pastel-dark);

        &.active {
            color: var(--color-medium-primary);
        }
    }

    button.toggle-button {
        background: var(--color-medium-primary) !important;

        .toggle-button-slider {
            //background: var(--color-accent-medium);
        }
    }
}
</style>
