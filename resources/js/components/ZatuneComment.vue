<script setup>
import ZatuneThinking from '../Shared/ZatuneThinking.vue';

const props = defineProps({
    comment: String
})
</script>

<template>
    <div class="zatune-comment">
        <div class="comment-bubble">
            {{ comment }}
        </div>
        <ZatuneThinking/>
    </div>
</template>

<style scoped lang="scss">
.zatune-comment {
    width: 100%;
    display: grid;
    gap: 1.6rem;
    margin-block: 4%;
    padding-inline: 4%;
    grid-template-columns: 1fr min(25%, 16rem);

    .comment-bubble {
        padding: 1.6rem 2rem;
        margin-block-end: 6.4rem;
        color: var(--color-dark-secondary);
        background: var(--color-accent-light);
        border-radius: 1.6rem 1.6rem 0.4rem 1.6rem;
        align-self: start;
        justify-self: end;
        line-height: 1.5;
    }

    svg {
        align-self: end;
    }
}
</style>
