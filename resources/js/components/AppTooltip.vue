<script setup>
import {ref} from "vue";

const visible = ref(false);
const message = ref("");
const tooltipStyle = ref({
    top: "0px",
    left: "0px",
});

const showTooltip = (newMessage, event) => {
    message.value = newMessage;
    visible.value = true;
    updatePosition(event);
}

const hideTooltip = () => {
    visible.value = false;
}

const updatePosition = (event) => {
    tooltipStyle.value = {
        top: `${event.clientY + 10}px`,
        left: `${event.clientX + 10}px`,
    };
}

defineExpose({
    showTooltip,
    hideTooltip,
});
</script>

<template>
    <div v-if="visible" :style="tooltipStyle" class="app-tooltip">
        {{ message }}
    </div>
</template>
