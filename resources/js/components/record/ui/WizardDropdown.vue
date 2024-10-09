<script setup>
import {ref, watch} from 'vue';

const props = defineProps({
    options: {type: Array, default: () => []},
    value: {type: String, default: ''},
    disabled: {type: Boolean, default: false},
    inputId: {type: String, required: false}
});

const emit = defineEmits(['input', 'change']);

const selectedValue = ref(props.value);

const handleChange = () => {
    emit('input', selectedValue.value);
    emit('change');
};

// Watch for changes to the `value` prop and update the internal selected value
watch(
    () => props.value,
    (newVal) => {
        selectedValue.value = newVal;
    }
);

// Watch for changes to the options and reset the selection if necessary
watch(
    () => props.options,
    (newOptions) => {
        if (!newOptions.find(option => option.data === selectedValue.value)) {
            selectedValue.value = '';
        }
    },
    {deep: true}
);
</script>

<template>
    <select
        :id="inputId"
        :disabled="disabled"
        v-model="selectedValue"
        @change="handleChange"
    >
        <option v-for="option in options" :key="option.data" :value="option.data">
            {{ option.label }}
        </option>
    </select>
</template>

<style scoped>
/* Add styles for the dropdown */
select {
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ccc;
    font-size: 14px;
}
</style>
