<script setup>
defineProps({
    align: {type: String, default: 'rtl'},
    speaker: String,
    ar: 'arb',
    en: 'eng',
});
</script>
<template>
    <div class="dialog-line-wrapper" :class="{ reverse: align === 'ltr' }">
        <div v-if="speaker" class="dialog-line-head">
            {{ speaker }}
        </div>
        <div class="dialog-line-body">
            <div class="dialog-arb">
                {{ ar }}
            </div>
            <div v-if="en" class="dialog-eng">
                {{ en }}
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.dialog-line-wrapper {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 0.8rem;
    font-family: var(--mono-font);
    overflow: hidden;
    direction: rtl;

    &.reverse {
        direction: ltr;

        .dialog-line-head {
            border-radius: 1.6rem 1.6rem 1.6rem 0.4rem;
        }
    }

    &:not(:has(.dialog-line-head)) {
        margin-block-start: 0;
    }

    .dialog-line-head {
        font-weight: 700;
        color: white;
        background: var(--color-medium-primary);
        padding: 0.8rem 1.6rem;
        display: flex;
        align-items: center;
        border-radius: 1.6rem 0.4rem 1.6rem 1.6rem;
    }

    .dialog-line-body {
        display: grid;
        gap: 0.4rem;
        padding: 1.2rem 2.4rem;
        border-radius: 1.6rem;
        background: white;
        font-size: 2.0rem;
        text-align: start;

        .dialog-eng {
            direction: ltr;
            width: 100%;
            font-family: var(--body-font);
            font-size: 1.4rem;
        }
    }
}
</style>
