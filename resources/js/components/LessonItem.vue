<script setup>
import {route} from "ziggy-js";
import {useUserStore} from "../stores/UserStore.js";
import WindowSection from "./WindowSection.vue";
import AppTip from "./AppTip.vue";

const UserStore = useUserStore();

const props = defineProps({
    lesson: Object,
})
</script>

<template>
    <div class="window-container lesson-item"
         :class="{hidden: !lesson.published}">
        <div class="window-section-head">
            <h1>lesson {{ lesson.global_position }}</h1>
            <Link v-if="UserStore.isAdmin" :href="route('lesson-planner.lesson', lesson.id)" class="material-symbols-rounded">
                edit
            </Link>
        </div>
        <div class="window-content-head" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="window-content-head-title">{{ lesson.title }}</div>
            <div class="lesson-item-progress">
                <div class="material-symbols-rounded" :class="{ completed: lesson.progress.stage > 1}">check</div>
                <div class="material-symbols-rounded" :class="{ completed: lesson.progress.stage > 2}">check</div>
            </div>
        </div>

        <WindowSection :visible="false">
            <template #title>
                <div class="window-section-head">
                    <h2>skills</h2>
                </div>
            </template>
            <template #content>
                <div v-if="lesson.document" class="lesson-item-skills">
                    <div v-if="lesson.document" class="lesson-item-skill-wrapper" v-for="skill in lesson.document?.skills">
                        <div class="lesson-skill-type featured-title">{{ skill.type }}</div>
                        <div class="lesson-skill-title">{{ skill.title }}</div>
                        <div class="lesson-skill-description">{{ skill.description }}</div>
                    </div>
                </div>
                <AppTip v-else>
                    <p>(No Skills have been added to the Lesson yet.)</p>
                </AppTip>
            </template>
        </WindowSection>
        <div class="window-footer">
            <Link :href="route('lessons.show', lesson.global_position)" :class="{ disabled: !UserStore.isAdmin && !UserStore.hasUnlockedLesson(lesson.id) }">
                open lesson
            </Link>
        </div>
    </div>
</template>

<style scoped lang="scss">
.lesson-item-progress {
    display: flex;
    flex-flow: row;
    justify-content: center;
    align-items: center;
    gap: 3.2rem;
    padding: 1.6rem;
    cursor: default;
    user-select: none;

    .material-symbols-rounded {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 6.4rem;
        height: 6.4rem;
        font-size: 3.2rem;
        color: white;
        background: var(--color-medium-primary);
        border-radius: 50%;
        opacity: 0.25;
        font-variation-settings: 'wght' 700;

        &.completed {
            opacity: 1;
        }
    }
}

.lesson-item {
    &.hidden {
        opacity: 0.66;
    }

    &.locked {
        opacity: 0.33;
        cursor: not-allowed;
        pointer-events: none;
        user-select: none;
    }

    .lesson-model-progress-wrapper {
        padding: 3.2rem;
    }
}

.lesson-item-skills {
    display: grid;
    padding-block: 1.6rem;

    .lesson-item-skill-wrapper {
        display: grid;
        grid-template-columns: max-content auto;
        align-items: end;
        gap: 0.8rem;
        padding-block: 3.2rem;
        margin-inline: 3.2rem;

        &:not(:last-child) {
            border-block-end: 0.2rem dotted var(--color-accent-medium);
        }

        .lesson-skill-type {
            color: var(--color-accent-medium);
            font-family: var(--display-font), serif;
            font-size: 2.4rem;
            text-transform: uppercase;
        }

        .lesson-skill-title {
            text-align: end;
            color: var(--color-dark-primary);
            font-family: var(--head-font), serif;
            font-size: 2.0rem;
            font-weight: 700;
        }

        .lesson-skill-description {
            grid-column: span 2;
            font-size: 1.6rem;
            font-family: var(--body-font), sans-serif;
        }
    }
}
</style>
