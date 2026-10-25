<script setup>
import {computed, onMounted} from 'vue';
import ProgressTaskItem from '../../Lessons/UI/ProgressTaskItem.vue';
import {useAcademyStateStore} from '../../../../stores/AcademyStateStore.js';
import AppTip from '../../../../components/AppTip.vue';
import LoadingSpinner from '../../../../Shared/LoadingSpinner.vue';
import ZatuneThinking from '../../../../Shared/ZatuneThinking.vue';
import {useRouter} from 'vue-router';

const router = useRouter();

const AcademyStateStore = useAcademyStateStore();
const studyPlan = computed(() => AcademyStateStore.studyPlan);

const cardTask = computed(() => {
    if (!studyPlan.value?.cards) return null;

    const remainingDue = studyPlan.value.cards.remaining_due ?? 0;
    const isDoneToday = studyPlan.value.cards.started && studyPlan.value.cards.completed;

    return {
        key: 'cards',
        icon: 'style',
        completed: isDoneToday,
        labelKey: `pages.academy.study-plan.cards.${studyPlan.value.cards.started ? 'review' : 'start'}-label`,
        metaKey: isDoneToday
            ? 'pages.academy.study-plan.cards.complete'
            : `pages.academy.study-plan.cards.${studyPlan.value.cards.started ? 'review' : 'start'}-meta`,
        metaParams: { count: remainingDue },
        actionKey: 'pages.academy.study-plan.cards.action',
        route: router.resolve({ name: 'card-dealer.review' }).href,
    };
});

const lessonTask = computed(() => {
    if (!studyPlan.value?.lesson) return null;

    const lesson = studyPlan.value.lesson.lesson;
    const lessonPosition = lesson?.global_position ?? '';

    const isDeckTask = studyPlan.value.lesson.action.model_type === 'deck';

    return {
        key: `lesson-${studyPlan.value.lesson.action.model_typee}`,
        icon: isDeckTask ? 'style' : 'assignment',
        completed: studyPlan.value.lesson.completed,
        labelKey: 'pages.academy.study-plan.lesson.label',
        labelParams: { lesson: lessonPosition },
        metaKey: isDeckTask
            ? 'pages.academy.study-plan.lesson.continue-deck'
            : 'pages.academy.study-plan.lesson.continue-activity',
        actionKey: isDeckTask
            ? 'pages.deck-master.buttons.start-quiz'
            : 'components.activity.start',
        route: isDeckTask
            ? router.resolve({
                name: 'deck-master.study',
                params: { deck: studyPlan.value.lesson.action.model_id },
            }).href
            : router.resolve({
                name: 'activities.activity',
                params: { activity: studyPlan.value.lesson.action.model_id },
            }).href,
    };
});

const tasks = computed(() => [cardTask.value, lessonTask.value].filter(Boolean));

const today = new Date();

const date = {
    day: String(today.getDate()).padStart(2, '0'),
    month: String(today.getMonth() + 1).padStart(2, '0'),
};

onMounted(() => {
    AcademyStateStore.ensureState().catch(() => {
    });
});
</script>

<template>
    <div class="study-plan-wrapper">
        <ZatuneThinking/>
        <section class="study-plan-container" aria-label="Today's Study Plan">
            <div class="study-plan-head">
                <div class="today-date">
                    <span>{{ date.day }}</span>
                    <span>/</span>
                    <span>{{ date.month }}</span>
                </div>
                <div class="featured-title m">{{ $t('pages.academy.study-plan.title') }}</div>
            </div>

            <LoadingSpinner v-if="AcademyStateStore.isLoading || (!studyPlan && !AcademyStateStore.hasError)"/>

            <AppTip v-else-if="AcademyStateStore.hasError">
                <p>{{ $t('pages.academy.study-plan.error') }}</p>
            </AppTip>

            <template v-else>
                <AppTip v-if="!AcademyStateStore.currentLesson">
                    <p><b>Congratulations! You've completed all the Lessons currently available in the Academy!</b></p>
                    <p>Here are some other things you can do now:</p>
                    <ul>
                        <li>Use the <b>Dialogs</b> to practice your speech & pronunciation with a partner.</li>
                        <li>Keep reviewing your <b>Cards</b> in the <b>Card Dealer</b> to raise your Mastery Level!</li>
                    </ul>
                </AppTip>

                <div class="academy-progress-tasks">
                    <template v-if="tasks.filter(i => i.completed).length > 0">
                        <div class="task-group-title">{{ $t('components.lesson.progress.completed-tasks') }}:</div>
                        <ProgressTaskItem v-for="item in tasks.filter(i => i.completed)"
                                          :key="item.key"
                                          :item="item"/>
                    </template>
                    <template v-if="tasks.filter(i => !i.completed).length > 0">
                        <div class="task-group-title">{{ $t('components.lesson.progress.next-task') }}:</div>
                        <ProgressTaskItem v-for="task in tasks.filter(i => !i.completed)" :key="task.key" :item="task"/>
                    </template>
                </div>
            </template>
        </section>

        <div v-if="tasks.filter(i => !i.completed).length === 0" class="progress-message">
            {{ $t('pages.academy.study-plan.tasks-completed') }}
        </div>
    </div>
</template>

<style scoped lang="scss">
.study-plan-wrapper {
    justify-self: center;
    display: grid;
    width: min(100%, 96rem);
    margin-block: 3.2rem 6.4rem;
    position: relative;

    @media (width >= 960px) {
        margin-block-start: 0;
    }

    .mascot-zatune {
        position: absolute;
        top: -10%;
        right: 5%;
        max-width: 15%;
        z-index: 1;
    }
}

.study-plan-container {
    display: grid;
    color: white;
    background: var(--color-medium-primary);
    border-radius: 3.2rem;
    margin-inline: 3.2rem;

    .academy-progress-tasks {
        padding: 0 2.4rem 2.4rem;
    }

    @media (width >= 960px) {
        margin-inline: 0;

        .academy-progress-tasks {
            padding: 0 3.2rem 3.2rem;
        }
    }

    .app-tip {
        box-shadow: none;
        border-radius: 0;
        border: none;
    }
}

.study-plan-head {
    display: flex;
    gap: 3.6rem;
    align-items: center;
    position: relative;
    background: var(--color-medium-secondary);
    border-radius: 3.2rem 3.2rem 0 0;
    padding: 0.8rem 2.4rem;

    .today-date {
        font-family: var(--display-font);
        font-size: 3.2rem;
        color: var(--color-pastel-medium);
        height: 2em;
        width: 2em;
        border-radius: 50%;
        background: var(--color-dark-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        user-select: none;
        rotate: -10deg;
        direction: ltr;

        span:first-child {
            margin-block-end: 0.5em;
        }

        span:last-child {
            margin-block-start: 0.5em;
        }
    }

    .featured-title {
        color: white;
    }
}

.progress-message {
    justify-self: start;
    text-align: center;
    margin-inline: 6.4rem;
    margin-block-start: 1.6rem;
    font-family: var(--head-font);
    font-weight: 700;
    background: var(--color-accent-light);
    color: var(--color-dark-primary);
    border-radius: 6.4rem;
    padding: 1.6rem 2.4rem;
    hyphens: none;

    @media (width >= 960px) {
        margin-inline: 3.2rem;
    }
}
</style>
