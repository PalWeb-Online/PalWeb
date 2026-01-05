<script setup>
import {route} from "ziggy-js";
import {useUserStore} from "../../../../stores/UserStore.js";
import {computed} from "vue";
import {router, usePage} from "@inertiajs/vue3";

const UserStore = useUserStore();

const props = defineProps({
    unit: Object,
    lesson: {type: Object, required: false},
    activeLesson: {type: String, required: false},
})

const page = usePage();

const editRoute = computed(() => {
    if (props.lesson) {
        return route('lesson-planner.lesson', props.lesson.id);

    } else if (props.unit) {
        return route('lesson-planner.unit', props.unit.id);

    } else {
        return route('lesson-planner.index');
    }
});

const isDraft = computed(() => {
    if (page.component === 'Academy/Units/Show' && !props.unit.published) {
        return true;

    } else if (page.component === 'Academy/Lessons/Show' && !props.lesson.published) {
        return true;
    }

    return false;
});
</script>

<template>
    <div class="unit-nav">
        <div class="unit-meta" v-if="isDraft">draft</div>
        <div class="academy-breadcrumbs" v-if="$page.component !== 'Academy/Units/Index'">
            <Link :href="route('units.index')">academy</Link>
            <Link v-if="unit" :href="route('units.show', unit.position)"
                  :class="{ active: !lesson }"
            >
                unit {{ unit.position }}
            </Link>
            <Link v-if="lesson" :href="route('lessons.show', lesson.global_position)"
                  class="active"
            >
                lesson {{ lesson.global_position }}
            </Link>
            <button v-if="UserStore.isAdmin"
                    @click="router.get(editRoute)" class="material-symbols-rounded">edit
            </button>
        </div>

        <div class="featured-title s">unit {{ unit.position }}: {{ unit.title }}</div>
        <div class="unit-progress-bar-wrapper">
            <Link :href="route('units.show', unit.position)" class="material-symbols-rounded"
                  :class="{ active: $page.component === 'Academy/Units/Show' }">
                home
            </Link>
            <div class="unit-progress-bar">
                <Link v-for="lesson in unit.lessons"
                      :href="route('lessons.show', lesson)"
                      :class="{
                        locked: !UserStore.isAdmin && !UserStore.hasUnlockedLesson(lesson.id),
                        unlocked: UserStore.isAdmin || UserStore.hasUnlockedLesson(lesson.id),
                        completed: lesson.completed,
                        active: activeLesson === lesson.global_position,
                        hidden: !UserStore.isAdmin && !lesson.published
                      }"
                >
                    {{ lesson.unit_position }}
                </Link>
                <Link v-if="UserStore.isAdmin && unit.lessons?.length < 9"
                      :href="route('lesson-planner.unit-lesson', unit)">+
                </Link>
            </div>
            <div class="material-symbols-rounded checkmark"
                 :class="{completed: !unit.lessons?.some(lsn => !lsn.completed)}">
                check
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.unit-nav {
    width: min(100%, 96rem);
    display: grid;
    gap: 1.6rem;
    margin-block: 3.2rem;
    padding-inline: 1.6rem;

    @media (min-width: 960px) {
        padding-inline: 0;
    }

    .unit-progress-bar-wrapper {
        width: 100%;
        display: grid;
        align-items: center;
        grid-template-columns: min-content auto min-content;
        gap: 0.8rem;

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
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .checkmark {
            background: var(--color-medium-primary);
            font-variation-settings: 'wght' 700;

            &:not(.completed) {
                opacity: 0.25;
            }
        }
    }

    .unit-progress-bar {
        display: grid;
        height: 3.6rem;
        grid-template-columns: repeat(9, 1fr);
        border-radius: 6.4rem;
        background: white;
        overflow: hidden;

        & > * {
            color: var(--color-medium-secondary);
            font-family: var(--head-font), serif;
            //font-size: clamp(0.8rem, 2vw, 1.6rem);
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-block-start: 0.2rem;


            &.unlocked:not(.completed) {
                background: var(--color-pastel-dark);
            }

            &.locked {
                opacity: 0.5;
                pointer-events: none;
            }

            &.completed {
                color: white;
                background: var(--color-medium-primary);
            }

            &.unlocked.active {
                color: white;
                background: var(--color-medium-secondary);
            }

            &.hidden {
                display: none;
                opacity: 0.33;
            }
        }
    }
}

.academy-breadcrumbs {
    display: flex;
    align-items: center;
    gap: 1.6rem;
    margin-block-end: 1.6rem;

    a {
        color: var(--color-dark-primary);
        font-family: var(--head-font), sans-serif;
        font-size: 1.6rem;
        font-weight: 700;
        text-transform: lowercase;
        padding: 0.6rem 1.6rem;
        background: var(--color-accent-light);
        border-radius: 6.4rem;
    }

    a.active {
        color: white;
        background: var(--color-medium-secondary);
    }

    button {
        color: var(--color-dark-primary)
    }
}
</style>
