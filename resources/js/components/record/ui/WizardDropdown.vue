<script setup>
import {ref, watch} from 'vue';

const props = defineProps({
  options: {type: Array, default: () => []},
  modelValue: {type: [String, Number], default: ''},
  disabled: {type: Boolean, default: false},
  inputId: {type: String, required: false}
});

const emit = defineEmits(['update:modelValue', 'change']);

const selectedValue = ref(props.modelValue);

const handleChange = () => {
  emit('update:modelValue', selectedValue.value);
  emit('change');
};

watch(
    () => props.modelValue,
    (newVal) => {
      selectedValue.value = newVal;
    }
);

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
