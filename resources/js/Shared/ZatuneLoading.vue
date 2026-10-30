<script setup>
import SpeechBubble from './SpeechBubble.vue';
import ZatuneThinking from './ZatuneThinking.vue';
import LoadingSpinner from './LoadingSpinner.vue';

defineProps({
    message: {
        type: String,
        required: false,
    },
});
</script>

<template>
    <div class="zatune-loading">
        <SpeechBubble v-if="message" :message="message"/>
        <div class="mascot-wrapper">
            <ZatuneThinking/>
            <LoadingSpinner/>
        </div>
    </div>
</template>

<style scoped lang="scss">
.window-container > .zatune-loading {
    padding: 1.6rem;
}

.zatune-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;

    .mascot-wrapper {
        width: clamp(14rem, 18vw, 16rem);
        flex-shrink: 0;
        position: relative;

        .loading-spinner {
            width: 30%;
            height: auto;
            position: absolute;
            top: 0;
            right: 0;
        }
    }
}
</style>
