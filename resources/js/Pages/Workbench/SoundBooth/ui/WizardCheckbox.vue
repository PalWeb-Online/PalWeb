<script setup>
import {watch} from 'vue';

const props = defineProps({
    value: {type: Boolean, default: false},
    disabled: {type: Boolean, default: false},
    inputId: {type: String, required: false}
});

const emit = defineEmits(['input']);

const handleChange = (event) => {
    emit('input', event.target.checked);
};

watch(
    () => props.value,
    (newVal) => {
        document.getElementById(props.inputId)?.setAttribute('checked', newVal);
    }
);

</script>

<template>
    <input
        type="checkbox"
        :id="inputId"
        :checked="value"
        :disabled="disabled"
        @change="handleChange"
    />
</template>
