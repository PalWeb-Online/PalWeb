<script setup>
import Layout from "../../../Shared/Layout.vue";
import {useUserStore} from "../../../stores/UserStore.js";
import {route} from "ziggy-js";
import StudyPlan from "./UI/StudyPlan.vue";
import AppHeading from "../../../components/AppHeading.vue";
import {onMounted} from "vue";
import {useAcademyStateStore} from "../../../stores/AcademyStateStore.js";
import DividerBlock from "../../../Shared/DividerBlock.vue";

defineProps({
    units: Object,
    lessons: Object,
})

defineOptions({
    layout: Layout,
})

const UserStore = useUserStore()
const AcademyStateStore = useAcademyStateStore()

onMounted(() => {
    AcademyStateStore.ensureState().catch(() => {
    });
})
</script>
<template>
    <Head title="Academy: Lessons"/>
    <div id="app-head">
        <h1>{{ $t('pages.academy.index.title') }}</h1>
        <DividerBlock/>
    </div>

    <div id="app-body">
        <StudyPlan/>
        <div class="unit-map">
            <div v-for="unit in units" :key="unit.id" class="unit-map-unit-wrapper">
                <AppHeading section="academy">
                    {{ $t('unit.number', {number: unit.position}) }}
                </AppHeading>
                <div class="unit-map-lessons-wrapper">
                    <div class="unit-map-lesson-button" v-for="lesson in unit.lessons" :key="lesson.id"
                         :class="{current: AcademyStateStore.isCurrentLesson(lesson.id)}"
                    >
                        <Link
                            :href="route('lessons.show', lesson)"
                            :class="{
                                locked: !UserStore.isAdmin && !AcademyStateStore.hasUnlockedLesson(lesson.id),
                                completed: AcademyStateStore.isCompletedLesson(lesson.id, lesson.completed),
                                draft: !lesson.published,
                                hidden: !UserStore.isAdmin && !lesson.published
                              }"
                        >
                            <span>{{ lesson.unit_position }}</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--        <LessonItem v-for="lesson in lessons" :key="lesson.id" :lesson="lesson"/>-->
</template>

<style scoped lang="scss">
.unit-map {
    display: grid;
    gap: 9.6rem;
    margin-block: 9.6rem 19.2rem;
    width: min(100%, 96rem);

    .unit-map-unit-wrapper {
        display: grid;
        gap: 4.8rem;
        justify-items: center;
        justify-content: center;
    }

    @media (width >= 960px) {
        margin-block: 0 9.6rem;

        .unit-map-unit-wrapper {
            justify-content: normal;
        }
    }
}

.unit-map-lessons-wrapper {
    display: flex;
    flex-flow: column;
    align-items: center;
    font-size: 4.8rem;
    width: 3em;
    gap: 1em 0.5em;

    @media (width >= 960px) {
        flex-flow: row wrap;
        justify-content: space-between;
        width: 100%;
        height: 3em;
        font-size: 3.6rem;
    }

    .unit-map-lesson-button {
        border-radius: 50%;
        position: relative;

        &.current:after {
            content: "";
            position: absolute;
            inset: -0.25em;
            color: var(--color-accent-light);
            border: 0.25em solid currentColor;
            border-radius: inherit;
            box-shadow: 0 0 0.25em currentColor, inset 0 0 0.125em currentColor;
            pointer-events: none;
            transition: transform 0.5s;
            animation: current-lesson-ring 1.6s ease-in-out infinite;
        }

        &:nth-child(odd) {
            align-self: flex-start;
        }

        &:nth-child(even) {
            align-self: flex-end;
        }
    }

    a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2em;
        height: 2em;
        font-family: var(--display-font);
        color: var(--color-medium-primary);
        border: 0.15em solid currentColor;
        background: currentColor;
        translate: 0 -0.15em;
        rotate: 1 0 0 30deg;
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 0 0.15em 0 var(--color-medium-secondary), 0 0.3em 0 var(--color-medium-secondary);
        transition: box-shadow 0.1s, translate 0.1s;

        span {
            color: var(--color-pastel-light);
            font-size: 2.5em;
            translate: 0.1em 0;
        }

        &:hover {
            translate: 0 0.15em;
            box-shadow: none !important;
        }

        &.completed {
            color: var(--color-medium-secondary);
            box-shadow: 0 0.15em 0 var(--color-dark-primary), 0 0.3em 0 var(--color-dark-primary);
        }

        &.locked, &.draft {
            opacity: 0.66;
        }

        &.locked {
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
</style>
