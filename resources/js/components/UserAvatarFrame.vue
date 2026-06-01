<script setup>

</script>

<template>
    <svg class="avatar-frame" viewBox="0 0 100 100">
        <circle class="c5" cx="49" cy="51" r="34"/>
        <circle class="c4" cx="57" cy="44" r="28"/>
        <circle class="c3" cx="38" cy="38" r="23"/>
        <circle class="c2" cx="66" cy="66" r="18"/>
        <circle class="c1" cx="30" cy="75" r="10"/>
    </svg>
</template>

<style scoped lang="scss">
.avatar-frame {
    position: absolute;
    inset: -2em;
    width: calc(100% + 4em);
    height: calc(100% + 4em);
    overflow: visible;
    pointer-events: none;

    circle {
        animation-play-state: paused !important;
        transition: transform 400ms cubic-bezier(.2, .8, .2, 1);
        transform-origin: center;
    }

    .c1 {
        fill: var(--color-medium-secondary);
        animation: rotate-ccw 41s linear infinite;
    }

    .c2 {
        fill: var(--color-accent-medium);
        animation: rotate-ccw 31s linear infinite;
    }

    .c3 {
        fill: var(--color-pastel-dark);
        animation: rotate-cw 23s linear infinite;
    }

    .c4 {
        fill: var(--color-medium-primary);
        animation: rotate-cw 17s linear infinite;
    }

    .c5 {
        fill: var(--color-dark-primary);
        animation: rotate-cw 11s ease-in-out infinite;
    }
}

.user-avatar:hover .avatar-frame circle {
    animation-play-state: running !important;
}

@keyframes rotate-cw {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

@keyframes rotate-ccw {
    from {
        transform: rotate(360deg);
    }
    to {
        transform: rotate(0deg);
    }
}
</style>
