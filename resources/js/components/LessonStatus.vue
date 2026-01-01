<script setup>
import {route} from "ziggy-js";

defineProps({
    lesson: Object,
    model: Object,
})
</script>

<template>
    <div class="lesson-status">
        <div style="background: var(--color-accent-light)">
            <Link class="lesson-title" :href="route('lessons.show', lesson.global_position)">
                Lesson {{ lesson.global_position }}
            </Link>

            <template v-if="lesson.progress">
                <div style="display: flex; align-items: center; gap: 0.4rem">
                    <div class="lock material-symbols-rounded" :class="{ unlocked: lesson.progress?.stage }">
                        {{ lesson.progress?.stage ? 'lock_open' : 'lock' }}
                    </div>
                    <div class="check material-symbols-rounded"
                         :class="{ active: lesson.progress?.stage > 1 }">check
                    </div>
                    <div class="check material-symbols-rounded"
                         :class="{ active: lesson.progress?.stage > 2 }">check
                    </div>
                </div>
            </template>
            <template v-else>
                Not Unlocked Yet
            </template>
        </div>

        <div style="flex-grow: 1; justify-content: space-between; font-weight: 700">
            <div>
                Times Scored:
                {{ lesson.scores_count[`${model.model_class}:${model.id}`] ?? 0 }}/{{ model.model_class === 'deck' ? '3' : '1'}}
            </div>
            <Link v-if="model.model_class === 'deck'" :href="route('deck-master.study', model)">Start Quiz</Link>
            <Link v-else-if="model.model_class === 'activity'" :href="route('activities.activity', model)">Start Activity</Link>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.lesson-status {
    display: flex;
    align-items: center;
    background: var(--color-pastel-medium);
    color: var(--color-dark-secondary);

    & > div {
        display: flex;
        align-items: center;
        gap: 1.6rem;
        padding: 0.8rem 1.6rem
    }

    .lesson-title {
        display: flex;
        align-items: center;
        text-transform: uppercase;
        font-family: var(--mono-font), monospace;
        font-size: 1.6rem;
        font-weight: 700;
    }

    .lock {
        color: var(--color-medium-secondary);
        opacity: 0.33;

        &.unlocked {
            opacity: 1;
        }
    }

    .check {
        border-radius: 50%;
        width: 2.4rem;
        height: 2.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        background: var(--color-medium-primary);
        font-size: 2.0rem;
        font-variation-settings: 'wght' 700;
        opacity: 0.33;
        user-select: none;

        &.active {
            opacity: 1;
        }
    }
}
</style>
