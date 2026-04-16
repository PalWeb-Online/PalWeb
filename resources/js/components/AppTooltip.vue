<script setup>
import {useTooltip} from "../composables/useTooltip.js";

const {
    message,
    isVisible,
    tooltipStyle,
    showTooltip,
    hideTooltip
} = useTooltip();

defineExpose({
    showTooltip,
    hideTooltip,
});
</script>

<template>
    <Teleport to="body">
        <div v-if="isVisible"
             :style="tooltipStyle"
             class="app-tooltip"
        >
            <slot>
                {{ message }}
            </slot>
        </div>
    </Teleport>
</template>
