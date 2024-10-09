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

// Watch the `value` prop to update the checkbox programmatically if needed
watch(
    () => props.value,
    (newVal) => {
        // Manually update the checked state if `value` changes externally
        document.getElementById(props.inputId)?.setAttribute('checked', newVal);
    }
);

</script>

<template>
  <span>
    <input
        type="checkbox"
        :id="inputId"
        :checked="value"
        :disabled="disabled"
        @change="handleChange"
    />
  </span>
</template>

<style scoped>
/* Add any necessary styles */
input[type="checkbox"] {
    margin-right: 10px;
}
</style>
