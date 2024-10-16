<script setup>
import { computed } from 'vue';

const props = defineProps({
    label: { type: String, required: false },
    icon: { type: String, required: false },
    flags: { type: String, default: '' }, // Can be used for different button styles
    framed: { type: Boolean, default: true },
    disabled: { type: Boolean, default: false },
    href: { type: String, default: '' },
    target: { type: String, default: '_self' }
});

const emit = defineEmits(['click']);

const buttonClass = computed(() => {
    return [
        'wizard-button',
        props.flags, // Assuming flags can be space-separated classes for styling (like 'progressive primary')
        { framed: props.framed, disabled: props.disabled }
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
        :href="href"
        :target="target"
    >
        <span v-if="icon" :class="`icon-${icon}`"></span>
        <span v-if="label">{{ label }}</span>
    </button>
</template>
