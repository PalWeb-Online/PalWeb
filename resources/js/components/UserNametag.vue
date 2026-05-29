<script setup>
const props = defineProps({
    user: Object,
    speaker: Object,
})
</script>

<template>
    <div class="user-name">
        <div class="user-name-ar">{{ speaker && user.private ? 'مجهول' : user.ar_name }}</div>
        <div class="user-name-en">
            <div>{{ speaker && user.private ? 'Speaker #' + speaker.id : user.name }}</div>
            <div>{{ speaker && user.private ? '[anonymous]' : '@' + user.username }}</div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.user-name {
    flex-grow: 1;
    display: flex;
    flex-flow: row-reverse wrap;
    gap: 1em;
    font-size: 2.4rem;
    line-break: anywhere;
    color: var(--color-dark-primary);

    * {
        text-box: trim-both cap alphabetic;
    }

    .user-name-ar {
        direction: rtl;
        font-size: 2em;
        font-family: var(--display-font);
    }

    .user-name-en {
        flex-grow: 1;
        font-weight: 700;
        display: grid;
        align-content: start;
        gap: 0.5em;

        & > *:nth-child(1) {
            font-size: 1em;
            font-family: var(--head-font);
        }

        & > *:nth-child(2) {
            font-size: 0.75em;
            font-family: var(--mono-font);
        }
    }
}
</style>
