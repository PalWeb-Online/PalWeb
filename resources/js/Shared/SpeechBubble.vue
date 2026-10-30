<script setup>
defineProps({
    message: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="speech-bubble">
        <div class="speech-bubble-body">
            {{ message }}
        </div>
        <svg class="speech-bubble-tail" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 37.33 23.24">
            <g id="Layer_1-2" data-name="Layer 1">
                <path class="cls-1" d="M22.87.03c.2,7.86,2.29,14,13.46,22.21C14.56,22.46,2.31,12.33.99,1.03"/>
            </g>
        </svg>
    </div>
</template>

<style scoped lang="scss">
.speech-bubble {
    position: relative;
    margin-block-end: 6.4rem;
}

.speech-bubble-body {
    inset: 0;
    border: 0.2rem solid var(--color-dark-secondary);
    border-radius: 2.4rem;
    background: var(--color-accent-light);
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 2rem 3rem;
    font-size: clamp(1.4rem, 1.8vw, 1.6rem);
}

.speech-bubble-tail {
    position: absolute;
    bottom: -1.6rem;
    right: 2.4rem;
    width: 3.2rem;
}

.cls-1 {
    fill: var(--color-accent-light);
    stroke: var(--color-dark-secondary);
    stroke-linejoin: round;
    stroke-width: 0.2rem;
    vector-effect: non-scaling-stroke;
}
</style>
