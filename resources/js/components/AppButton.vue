<script setup>
import { computed } from 'vue';

const props = defineProps({
    label: { type: String, default: '' },
    class: { type: String, default: '' },
    active: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
});

const emit = defineEmits(['click']);

const buttonClass = computed(() => {
    return [
        'app-button',
        props.class,
        { active: props.active, disabled: props.disabled },
    ];
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
