<script setup>
import {route} from "ziggy-js";
import {useUserStore} from "../../../../stores/UserStore.js";
import {computed, nextTick, onBeforeUnmount, onMounted, ref, watch} from "vue";
import {router, usePage} from "@inertiajs/vue3";
import {useAcademyStateStore} from "../../../../stores/AcademyStateStore.js";

const UserStore = useUserStore();
const AcademyStateStore = useAcademyStateStore();

const props = defineProps({
    unit: {type: Object, required: true},
    activeLesson: {type: Object, required: false},
})

const page = usePage();
const isHovering = ref(false);
const collapseProgress = ref(0);
const collapsibleInner = ref(null);
const expandedHeight = ref(0);
let animationFrameId = null;
let resizeObserver = null;

const editRoute = computed(() => {
    if (props.activeLesson) {
        return route('lesson-planner.lesson', props.activeLesson.id);

    } else if (props.unit) {
        return route('lesson-planner.unit', props.unit.id);

    } else {
        return route('lesson-planner.index');
    }
});

const isDraft = computed(() => {
    if (page.component === 'Academy/Units/Show' && !props.unit.published) {
        return true;

    } else if (page.component === 'Academy/Lessons/Show' && props.activeLesson && !props.activeLesson.published) {
        return true;
    }

    return false;
});

const visibleRatio = computed(() => {
    if (isHovering.value) {
        return 1;
    }

    return Math.max(0, 1 - collapseProgress.value);
});

const collapsibleStyle = computed(() => {
    if (!expandedHeight.value) {
        return {};
    }

    const height = expandedHeight.value * visibleRatio.value;

    return {
        height: `${height}px`,
        opacity: visibleRatio.value,
        transform: `translateY(${(1 - visibleRatio.value) * -0.8}rem)`,
    };
});

const measureCollapsible = () => {
    expandedHeight.value = collapsibleInner.value?.scrollHeight ?? 0;
};

const updateCollapseProgress = () => {
    animationFrameId = null;

    const collapseDistance = Math.max(expandedHeight.value, 1);
    collapseProgress.value = Math.min(window.scrollY / collapseDistance, 1);
};

const requestCollapseProgressUpdate = () => {
    if (animationFrameId !== null) {
        return;
    }

    animationFrameId = requestAnimationFrame(updateCollapseProgress);
};

const handleResize = () => {
    measureCollapsible();
    requestCollapseProgressUpdate();
};

const expandNav = () => {
    isHovering.value = true;
};

const collapseNav = () => {
    isHovering.value = false;
};

const handleFocusOut = (event) => {
    if (event.currentTarget?.contains(event.relatedTarget)) {
        return;
    }

    collapseNav();
};

onMounted(() => {
    AcademyStateStore.ensureState().catch(() => {
    });

    nextTick(() => {
        measureCollapsible();
        requestCollapseProgressUpdate();

        if (collapsibleInner.value) {
            resizeObserver = new ResizeObserver(handleResize);
            resizeObserver.observe(collapsibleInner.value);
        }
    });

    window.addEventListener('scroll', requestCollapseProgressUpdate, {passive: true});
    window.addEventListener('resize', handleResize);
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', requestCollapseProgressUpdate);
    window.removeEventListener('resize', handleResize);

    if (animationFrameId !== null) {
        cancelAnimationFrame(animationFrameId);
    }

    resizeObserver?.disconnect();
});

watch(() => props.unit, () => {
    nextTick(handleResize);
});
</script>

<template>
    <div
        class="unit-nav"
        @mouseenter="expandNav"
        @mouseleave="collapseNav"
        @focusin="expandNav"
        @focusout="handleFocusOut"
    >
        <div class="unit-nav-collapsible" :style="collapsibleStyle">
            <div ref="collapsibleInner" class="unit-nav-collapsible-inner">
                <div class="unit-meta" v-if="isDraft">draft</div>
                <div class="unit-title">
                    <Link :href="route('units.index')" class="material-symbols-rounded">arrow_back</Link>
                    <div class="unit-title-text">
                        {{ $t('unit.number', {number: unit.position}) }}: {{ unit.title }}
                    </div>
                    <div class="material-symbols-rounded checkmark"
                         :class="{completed: !unit.lessons?.some(lsn => !AcademyStateStore.isCompletedLesson(lsn.id, lsn.completed))}">
                        check
                    </div>
                    <button v-if="UserStore.isAdmin"
                            @click="router.get(editRoute)" class="material-symbols-rounded">edit
                    </button>
                </div>
            </div>
        </div>
        <div class="unit-progress-bar-wrapper">
            <Link :href="route('units.show', unit.position)" class="material-symbols-rounded"
                  :class="{ active: $page.component === 'Academy/Units/Show' }">
                home
            </Link>
            <div class="unit-progress-bar">
                <Link v-for="lesson in unit.lessons"
                      :key="lesson.id"
                      :href="route('lessons.show', lesson)"
                      :class="{
                        hidden: !UserStore.isAdmin && !lesson.published,
                        locked: !UserStore.isAdmin && !AcademyStateStore.hasUnlockedLesson(lesson.id),
                        unlocked: UserStore.isAdmin || AcademyStateStore.hasUnlockedLesson(lesson.id),
                        completed: AcademyStateStore.isCompletedLesson(lesson.id, lesson.completed),
                        active: activeLesson?.global_position === lesson.global_position,
                        current: AcademyStateStore.isCurrentLesson(lesson.id),
                      }"
                >
                    <div>
                        <span>{{ lesson.unit_position }}</span>
                    </div>
                </Link>
                <Link v-if="UserStore.isAdmin && unit.lessons?.length < 9"
                      class="new-lesson-link"
                      :href="route('lesson-planner.unit-lesson', unit)">+
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
@use "@styles/variables";

.unit-nav {
    --unit-nav-sticky-offset: 4.8rem;
    position: sticky;
    top: var(--unit-nav-sticky-offset);
    width: 100%;
    display: grid;
    background: var(--color-accent-light);
    border-block-end: 0.1rem solid var(--color-dark-primary);
    box-shadow: 0 0.3rem 0 rgb(0 0 0 / 25%);
    z-index: 2;

    @media (width >= 960px) {
        --unit-nav-sticky-offset: 3.6rem;
        margin-block-end: 3.2rem;
        padding-inline: 0;
    }

    .unit-nav-collapsible {
        overflow: hidden;
        will-change: height, opacity, transform;
        transition: height 160ms ease,
        opacity 140ms ease,
        transform 160ms ease;
    }

    .unit-nav-collapsible-inner {
        display: grid;
    }

    .unit-title {
        display: flex;
        gap: 1.6rem;
        align-items: center;
        margin-block: 2.4rem;
        margin-inline: 1.2rem;

        .unit-title-text {
            @include variables.featured-title;
            color: var(--color-dark-primary);
            font-size: 3.2rem;
        }

        a, .checkmark {
            font-variation-settings: 'wght' 700;
        }

        .checkmark {
            font-size: 2.0rem;
            border-radius: 50%;
            color: white;
            padding: 0.4rem;
            background: var(--color-medium-primary);

            &:not(.completed) {
                opacity: 0.25;
            }
        }

        a, button {
            color: var(--color-dark-primary)
        }
    }

    .unit-progress-bar-wrapper {
        width: 100%;
        display: grid;
        align-items: center;
        border-block-start: 0.1rem solid var(--color-dark-primary);
        grid-template-columns: min-content minmax(0, 1fr);

        & > a.material-symbols-rounded {
            padding-block-end: 0.2rem;
            background: var(--color-medium-primary);

            &.active {
                background: var(--color-medium-secondary);
            }
        }

        .material-symbols-rounded {
            width: 3.6rem;
            height: 3.6rem;
            font-size: 2.0rem;
            color: white;
            background: var(--color-dark-primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }
}

.unit-progress-bar {
    display: grid;
    height: 3.6rem;
    grid-template-columns: repeat(9, 1fr);
    background: white;

    a {
        color: var(--color-medium-secondary);
        font-family: var(--display-font), serif;
        font-size: 2.4rem;
        display: flex;
        justify-content: center;

        div {
            display: flex;
            align-items: center;
            justify-content: center;
            aspect-ratio: 1;
            position: relative;
        }

        span {
            text-box: trim-both cap alphabetic;
        }

        &.current div:after {
            content: "";
            position: absolute;
            inset: -0.25em;
            color: var(--color-pastel-medium);
            border: 0.25em solid currentColor;
            border-radius: 50%;
            box-shadow: 0 0 0.25em currentColor, inset 0 0 0.125em currentColor;
            pointer-events: none;
            transition: transform 0.5s;
            animation: current-lesson-ring 1.6s ease-in-out infinite;
        }

        &.unlocked:not(.completed) {
            background: var(--color-pastel-dark);
        }

        &.completed {
            color: white;
            background: var(--color-medium-primary);
        }

        &.unlocked.active {
            color: white;
            background: var(--color-medium-secondary);
        }

        &.locked {
            opacity: 0.5;
            pointer-events: none;
        }

        &.hidden {
            display: none;
        }
    }
}

@keyframes current-lesson-ring {
    0%, 100% {
        opacity: 0.75;
        transform: scale(1);
    }

    50% {
        opacity: 1;
        transform: scale(1.2);
    }
}

@media (prefers-reduced-motion: reduce) {
    .unit-nav .unit-nav-collapsible {
        transition: none;
    }
}
</style>
