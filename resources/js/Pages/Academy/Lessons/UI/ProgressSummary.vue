<script setup>
import {computed} from "vue";
import {route} from "ziggy-js";
import ProgressTaskItem from "./ProgressTaskItem.vue";

const props = defineProps({
    lesson: {
        type: Object,
        required: true,
    },
    currentTab: {
        type: String,
        required: false,
        default: 'deck',
    },
});

const emit = defineEmits(['update:currentTab']);

const stage = computed(() => Number(props.lesson.progress?.stage ?? 0));

const scoreCount = (modelClass) => props.lesson.progress?.scores_count?.[modelClass] ?? 0;

const stages = computed(() => [
    {
        key: 'deck',
        index: 1,
        labelKey: 'components.deck.title',
        unlocked: stage.value >= 1,
        completed: stage.value > 1,
    },
    {
        key: 'skills',
        index: 2,
        labelKey: 'components.lesson.sections.skills',
        unlocked: stage.value >= 2,
        completed: stage.value > 2,
    },
    {
        key: 'dialog',
        index: 3,
        labelKey: 'components.dialog.title',
        unlocked: stage.value >= 3,
        completed: stage.value >= 3,
    },
]);

const progressItems = computed(() => {
    const items = [
        {
            key: 'deck',
            labelKey: 'components.lesson.tasks.deck.label',
            metaKey: 'components.lesson.tasks.deck.meta',
            metaParams: {count: scoreCount('deck'), total: 3},
            actionKey: 'pages.deck-master.buttons.start-quiz',
            icon: 'style',
            count: scoreCount('deck'),
            total: 3,
            completed: scoreCount('deck') >= 3,
            active: stage.value === 1,
            route: props.lesson.deck ? route('deck-master.study', props.lesson.deck) : null,
        },
    ];

    if (stage.value >= 2) {
        items.push({
            key: 'activity',
            labelKey: 'components.lesson.tasks.activity.label',
            metaKey: 'components.lesson.tasks.activity.meta',
            metaParams: {count: scoreCount('activity'), total: 1},
            actionKey: 'components.activity.start',
            icon: 'assignment',
            count: scoreCount('activity'),
            total: 1,
            completed: scoreCount('activity') >= 1,
            active: stage.value === 2,
            route: props.lesson.activity ? route('activities.activity', props.lesson.activity) : null,
        });
    }

    if (stage.value >= 3) {
        items.push({
            key: 'dialog',
            labelKey: 'components.lesson.tasks.dialog.label',
            metaKey: 'components.lesson.tasks.dialog.meta',
            icon: 'forum',
            completed: false,
            active: stage.value === 3,
            route: null,
        });
    }

    return items;
});

const currentStagePosition = computed(() => {
    const index = stages.value.findIndex((lessonStage) => lessonStage.key === props.currentTab);

    return Math.max(index, 0);
});

const selectStage = (lessonStage) => {
    if (!lessonStage.unlocked) return;

    emit('update:currentTab', lessonStage.key);
};
</script>

<template>
    <section class="lesson-progress-summary" aria-label="Lesson progress">
        <div class="lesson-progress-title">{{ $t('components.lesson.progress.stages') }}</div>

        <div class="lesson-stages" :style="{ '--current-stage-position': currentStagePosition }">
            <div class="current-stage-indicator" aria-hidden="true"></div>

            <div v-for="lessonStage in stages"
                    :key="lessonStage.key"
                    class="lesson-stage-button"
                    :style="{ gridColumn: lessonStage.index }"
                    :class="{
                        active: currentTab === lessonStage.key,
                        current: stage === lessonStage.index,
                        complete: lessonStage.completed && stage !== lessonStage.index,
                        disabled: !lessonStage.unlocked
                    }"
                    @click="selectStage(lessonStage)">
                <div class="lesson-stage-index">
                    <span v-if="!lessonStage.unlocked" class="material-symbols-rounded">lock</span>
                    <span v-else>{{ lessonStage.index }}</span>
                </div>
                <div class="featured-title m" v-if="lessonStage.unlocked">{{ $t(lessonStage.labelKey) }}</div>
            </div>
        </div>

        <div class="academy-progress-tasks">
            <template v-if="progressItems.filter(i => i.completed).length > 0">
                <div class="task-group-title">{{ $t('components.lesson.progress.completed-tasks') }}:</div>
                <ProgressTaskItem v-for="item in progressItems.filter(i => i.completed)"
                                  :key="item.key"
                                  :item="item"/>
            </template>

            <div class="task-group-title">{{ $t('components.lesson.progress.next-task') }}:</div>
            <ProgressTaskItem v-for="item in progressItems.filter(i => !i.completed)"
                              :key="item.key"
                              :item="item"/>
        </div>
    </section>
</template>

<style scoped lang="scss">
@use "@styles/variables";

.lesson-progress-summary {
    display: grid;
    gap: 1.6rem;
    color: white;
    background: var(--color-medium-primary);
    padding: 2.4rem;

    .lesson-progress-title {
        @include variables.featured-title;
        font-size: 4.8rem;
        color: white;
        margin-block-end: 1.6rem;
    }

    @media (width >= 960px) {
        padding: 3.2rem;
    }
}

.lesson-stages {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    justify-items: center;
    gap: 0.8rem;
    font-size: clamp(0.7rem, 1.8vw, 1.6rem);

    .current-stage-indicator {
        grid-column: 1 / -1;
        grid-row: 1;
        justify-self: start;
        align-self: center;
        display: grid;
        place-items: center;
        width: calc((100% - 1.6rem) / 3);
        height: 100%;
        pointer-events: none;
        z-index: 0;
        transform: translateX(calc(var(--current-stage-position) * (100% + 0.8rem) * var(--to-inline-end)));
        transition: transform 0.2s ease-in-out;

        &::before {
            content: '';
            font-size: 16em;
            height: 0.8em;
            width: 0.8em;
            border-radius: 50%;
            background: var(--color-accent-medium);
        }
    }

    .lesson-stage-button {
        grid-row: 1;
        display: grid;
        place-items: center;
        grid-template-areas: 'overlap';
        user-select: none;
        cursor: pointer;
        appearance: none;
        width: 100%;
        border: 0;
        background: transparent;
        padding: 0.4rem;
        border-radius: 2.4rem;
        transition: background 0.2s, box-shadow 0.2s, opacity 0.2s;
        position: relative;
        z-index: 1;

        & > * {
            grid-area: overlap;
        }

        .lesson-stage-index {
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--display-font), serif;
            font-size: 16em;
            width: 0.75em;
            aspect-ratio: 1;
            border-radius: 50%;
            color: var(--color-dark-primary);
            background: var(--color-medium-secondary);
            overflow: hidden;
            z-index: 0;
            transition: rotate 0.1s, box-shadow 0.2s, background 0.2s;

            span {
                text-box: trim-both cap alphabetic;
            }

            .material-symbols-rounded {
                font-size: 0.24em;
                font-variation-settings: 'wght' 700;
            }
        }

        .featured-title {
            color: var(--color-medium-secondary);
            background: var(--color-accent-light);
            font-size: 3em;
            padding-inline: 0.3em;
            z-index: 1;
        }

        &:not(.disabled) {
            &:hover {
                .lesson-stage-index {
                    rotate: 9deg;
                }
            }
        }

        &.current {
            .lesson-stage-index {
                animation: current-stage-pulse 2.4s ease-in-out infinite;
            }
        }

        &.active {
            .featured-title {
                color: white;
                background: var(--color-medium-secondary);
            }

            .lesson-stage-index {
                background: var(--color-accent-light);
                rotate: 9deg;
            }
        }

        &.disabled {
            pointer-events: none;
            cursor: not-allowed;
            opacity: 0.72;

            .lesson-stage-index {
                color: var(--color-accent-light);
                background: var(--color-dark-primary);
            }
        }
    }
}

@keyframes current-stage-pulse {
    0%,
    100% {
        box-shadow: 0 0 2.4rem 0 rgb(255 255 255 / 20%);
    }

    50% {
        box-shadow: 0 0 2.4rem 1.2rem rgb(255 255 255 / 40%);
    }
}
</style>
