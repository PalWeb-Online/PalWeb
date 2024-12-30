<script setup>
import { computed } from 'vue';

const props = defineProps({
    label: { type: String, default: '' },
    class: { type: String, default: '' },
    disabled: { type: Boolean, default: false },
});

const emit = defineEmits(['click']);

const buttonClass = computed(() => {
    return [
        'app-button',
        props.class,
        { disabled: props.disabled }
    ].filter(Boolean).join(' ');
});

const handleClick = (event) => {
    if (!props.disabled) {
        emit('click', event);
    }
};
</script>

<template>
    <button
        :class="buttonClass"
        :disabled="disabled"
        @click="handleClick"
    >
        {{ label }}
    </button>
</template>
