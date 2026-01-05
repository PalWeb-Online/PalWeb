<script setup>
import {route} from "ziggy-js";

defineProps({
    lesson: Object,
    model: Object,
})
</script>

<template>
    <div class="lesson-status">
        <div style="background: var(--color-medium-secondary); color: white;">
            <Link class="lesson-title" :href="route('lessons.show', lesson.global_position)">
                Lesson {{ lesson.global_position }}
            </Link>

            <div style="display: flex; align-items: center; gap: 0.8rem">
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
        </div>

        <div style="flex-grow: 1; justify-content: space-between; font-weight: 700">
            <div v-if="['deck', 'activity'].includes(model.model_class)">
                Scored 100%
                ({{
                    lesson.scores_count[`${model.model_class}:${model.id}`] ?? 0
                }}/{{ model.model_class === 'deck' ? '3' : '1' }})
            </div>
            <Link v-if="model.model_class === 'deck'" :href="route('deck-master.study', model)">Start Quiz</Link>
            <Link v-else-if="model.model_class === 'activity'" :href="route('activities.activity', model)">Start
                Activity
            </Link>
        </div>
    </div>
</template>

<style scoped lang="scss">
.lesson-status {
    display: flex;
    align-items: center;
    background: var(--color-pastel-dark);
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
        color: var(--color-accent-medium);

        &.unlocked {
            color: white;
        }
    }

    .check {
        border-radius: 50%;
        width: 2.4rem;
        height: 2.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--color-medium-secondary);
        background: white;
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
