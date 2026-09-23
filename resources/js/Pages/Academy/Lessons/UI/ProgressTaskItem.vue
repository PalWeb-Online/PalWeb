<script setup>
defineProps({
    item: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="lesson-progress-item"
         :class="{ complete: item.completed }">
        <div class="lesson-progress-item-main">
            <span class="material-symbols-rounded">{{ item.completed ? 'check' : item.icon }}</span>
            <div>
                <div class="lesson-progress-label">
                    {{ $t(item.labelKey, item.labelParams ?? {}) }}
                </div>
                <div v-if="item.metaKey" class="lesson-progress-meta">
                    {{ $t(item.metaKey, item.metaParams ?? {}) }}
                </div>
            </div>
        </div>

        <Link v-if="item.route && !item.completed"
              class="lesson-progress-action"
              :href="item.route">
            {{ $t(item.actionKey) }}
        </Link>
    </div>
</template>

<style scoped lang="scss">
.lesson-progress-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.6rem;
    color: var(--color-dark-primary);
    background: var(--color-accent-light);
    border-radius: 1.6rem;
    padding: 1.2rem 1.6rem;

    &.complete {
        opacity: 0.72;
    }
}

.lesson-progress-item-main {
    display: flex;
    align-items: center;
    gap: 1.2rem;
    min-width: 0;

    & > .material-symbols-rounded {
        flex: 0 0 auto;
        width: 3.6rem;
        height: 3.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        background: var(--color-medium-secondary);
        border-radius: 50%;
        font-size: 2.2rem;
        font-variation-settings: 'wght' 700;
    }
}

.lesson-progress-label {
    font-family: var(--head-font), sans-serif;
    font-size: 1.6rem;
    font-weight: 700;
}

.lesson-progress-meta {
    font-size: 1.4rem;
    font-weight: 700;
    line-height: 1.3;
}

.lesson-progress-action,
.lesson-progress-complete {
    flex: 0 0 auto;
    color: white;
    background: var(--color-medium-secondary);
    border-radius: 6.4rem;
    font-size: 1.4rem;
    font-weight: 700;
    padding: 0.8rem 1.2rem;
    text-transform: capitalize;
}

.lesson-progress-complete {
    min-width: 5.6rem;
    text-align: center;
}
</style>
