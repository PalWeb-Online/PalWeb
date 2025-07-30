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

function toggle() {
    emit('update:modelValue', !props.modelValue);
}
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
                @click="toggle"
            >
                <div class="toggle-button-slider"></div>
            </button>
            <div :class="modelValue ? 'active' : ''">{{ optionB }}</div>
        </div>
    </div>
</template>
