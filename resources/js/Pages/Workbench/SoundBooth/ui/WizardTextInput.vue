<script setup>
const props = defineProps({
    value: {type: String, default: ''},
    placeholder: {type: String, default: ''},
    disabled: {type: Boolean, default: false},
    inputId: {type: String, required: false},
    label: {type: String, default: ''}
});

const emit = defineEmits(['input', 'enter']);

const onInput = (event) => {
    emit('input', event.target.value);
};

const onEnter = () => {
    emit('enter');
};
</script>

<template>
    <div>
        <!-- Optional label -->
        <label v-if="label" :for="inputId">{{ label }}</label>

        <!-- Text input field -->
        <input
            type="text"
            :id="inputId"
            :placeholder="placeholder"
            :disabled="disabled"
            :value="value"
            @input="onInput"
            @keyup.enter="onEnter"
        />
    </div>
</template>

<style scoped>
input[type="text"] {
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ccc;
    font-size: 14px;
    width: 100%;
    box-sizing: border-box;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}
</style>
