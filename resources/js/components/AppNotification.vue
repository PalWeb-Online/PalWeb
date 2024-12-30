<script setup>
import { ref } from 'vue';

const isHidden = ref(true);
const message = ref('');
const type = ref('success');

const showNotification = (msg, msgType = 'success', duration = 3000) => {
    message.value = msg;
    type.value = msgType;
    isHidden.value = false;

    setTimeout(() => {
        isHidden.value = true;
    }, duration);
};

defineExpose({
    showNotification,
});
</script>

<template>
    <div
        class="app-notification"
        :class="[type, { hidden: isHidden }]"
    >
        {{ message }}
    </div>
</template>
