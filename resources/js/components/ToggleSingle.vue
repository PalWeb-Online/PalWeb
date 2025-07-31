<script setup>
import { computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true
    },
    label: {
        type: String,
        default: ''
    }
});
const emit = defineEmits(['update:modelValue']);

const isActive = computed(() => props.modelValue);

function toggle() {
    emit('update:modelValue', !props.modelValue);
}
</script>

<template>
    <div class="toggle-button-wrapper">
        <button
            type="button"
            @click="toggle"
            class="toggle-button"
            :class="{ active: isActive }"
        >
            <div class="toggle-button-slider"></div>
        </button>
        <div>{{ label }}</div>
    </div>
</template>
