<script setup>
defineProps({
    gloss: Object,
    position: Number,
})
</script>

<template>
    <div class="gloss-item-container">
        <div class="gloss-item">
            <div class="gloss-item-label">
                {{ position }}
            </div>
            <div class="gloss-item-content">
                <div v-for="attribute in gloss.attributes" class="gloss-item-attribute">
                    <template v-if="attribute.category">[{{ attribute.category }}]</template>
                    {{ attribute.attribute }}
                </div>
                <div class="gloss-item-content-gloss">
                    {{ gloss.gloss }}
                </div>
                <slot name="relatives"/>
            </div>
        </div>
        <slot name="sentences"/>
    </div>
</template>

<style scoped lang="scss">
.gloss-item-container {
    display: grid;
    gap: 1.6rem;

    &:not(:last-child) {
        padding-block-end: 3.2rem;
        border-block-end: 0.1rem solid var(--color-medium-primary);
    }

    & > a {
        padding-block: 0.8rem;
        margin-inline-end: 3.2rem;
        justify-self: end;
        color: var(--color-dark-primary);
        font-weight: 700;
    }
}

.gloss-item {
    display: grid;
    align-items: center;
    row-gap: 1.6rem;

    @media (width >= 720px) {
        grid-template-columns: calc(9.6rem * 1.25) 1fr;
    }

    .gloss-item-label {
        justify-self: center;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--display-font);
        font-size: 9.6rem;
        height: 0.8em;
        width: 0.8em;
        border-radius: 50%;
        padding-block-end: 1.2rem;
        color: var(--color-dark-primary);
        background: var(--color-pastel-medium);
        transform: rotate(-6deg);
    }

    .gloss-item-content {
        display: grid;
        gap: 0.8rem;
        padding-inline: 1.6rem;

        .gloss-item-attribute {
            color: var(--color-medium-primary);
            font-weight: 700;
        }

        .gloss-item-content-gloss {
            font-size: 1.8rem;
        }
    }
}
</style>
