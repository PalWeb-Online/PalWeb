<script setup>
import Layout from "../../../Shared/Layout.vue";
import DialogContainer from "../../../components/DialogContainer.vue";
import {onMounted, ref, watch} from "vue";
import UnitNav from "../Units/UI/UnitNav.vue";
import SkillContainer from "../Lessons/UI/SkillContainer.vue";
import DeckContainer from "../../../components/DeckContainer.vue";
import ActivityContainer from "../../../components/ActivityContainer.vue";
import AppTip from "../../../components/AppTip.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useLessonViewer} from "../../../composables/lessons/useLessonViewer.js";
import ProgressSummary from "./UI/ProgressSummary.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    unitId: {
        type: Number,
        required: false,
    },
    lessonId: {
        type: Number,
        required: true,
    },
})

const {
    lesson,
    lessonNotFound,
    isLoadingLesson,
    unit,
    loadLesson,
    reloadLesson,
} = useLessonViewer();

const currentTab = ref('deck');

const tabForStage = (stage) => {
    if (stage >= 3) return 'dialog';
    if (stage >= 2) return 'skills';

    return 'deck';
};

onMounted(async () => {
    await loadLesson(props.lessonId);
    currentTab.value = tabForStage(lesson.value?.progress?.stage);
});

watch([() => props.lessonId, () => props.unitId], async () => {
    currentTab.value = 'deck';
    await reloadLesson(props.lessonId);
    currentTab.value = tabForStage(lesson.value?.progress?.stage);
});
</script>
<template>
    <Head :title="`Academy: Lesson ${lesson?.global_position}`"/>
    <UnitNav v-if="unit" :unit="unit" :activeLesson="lesson"/>

    <LoadingSpinner v-if="isLoadingLesson"/>
    <AppTip v-else-if="lessonNotFound">
        <p>{{ $t('pages.common.not-found', {model: $t('actions.models.lesson')}) }}</p>
    </AppTip>
    <template v-else-if="lesson">
        <div class="lesson-data-container">
            <div class="lesson-data-head">
                <div class="lesson-head-position">{{ $t('lesson.key-index', {index: lesson.global_position}) }}:</div>
                <div class="lesson-head-title">{{ lesson.title }}</div>
            </div>
            <div class="lesson-data-body">
                <div>{{ lesson.description ?? $t('pages.lessons.show.no-description') }}</div>
                <div v-if="lesson.document" class="lesson-skill-summary">
                    <div style="font-weight: 700">{{ $t('pages.lessons.show.skill-list') }}</div>
                    <ul style="margin-block: 1.6rem">
                        <li v-for="skill in lesson.document.skills">
                            {{ skill.description }}
                        </li>
                    </ul>
                </div>
            </div>
            <ProgressSummary :lesson="lesson" v-model:current-tab="currentTab"/>
        </div>

        <div id="app-body" v-show="currentTab === 'deck'">
            <template v-if="lesson.deck">
                <DeckContainer :model="lesson.deck"/>
            </template>
            <AppTip v-else>
                <p>{{ $t('pages.lessons.show.missing-deck') }}</p>
            </AppTip>
        </div>
        <div id="app-body" v-if="lesson.progress.stage > 1" v-show="currentTab === 'skills'">
            <SkillContainer v-for="skill in lesson.document?.skills" :skill="skill"/>

            <template v-if="lesson.activity">
                <div class="featured-title l" style="margin-block: 3.2rem">{{ $t('pages.lessons.show.ready') }}</div>
                <ActivityContainer v-if="lesson.activity" :model="lesson.activity"/>
            </template>
            <AppTip v-else>
                <p>{{ $t('pages.lessons.show.missing-activity') }}</p>
            </AppTip>
        </div>
        <div id="app-body" v-if="lesson.progress.stage > 2" v-show="currentTab === 'dialog'">
            <DialogContainer v-if="lesson.dialog" :model="lesson.dialog"/>
            <AppTip v-else>
                <p>{{ $t('pages.lessons.show.missing-dialog') }}</p>
            </AppTip>
        </div>
    </template>
</template>

<style lang="scss" scoped>
@use "@styles/variables";

.lesson-data-container {
    justify-self: center;
    display: grid;
    overflow: hidden;
    color: var(--color-dark-primary);
    background: var(--color-accent-light);

    @media (width >= 960px) {
        width: min(100%, 96rem);
        border-radius: 3.2rem;
    }
}

.lesson-data-head {
    display: grid;
    gap: 1.6rem;
    color: white;
    background: var(--color-medium-secondary);
    padding: 3.2rem 3.2rem 1.6rem;


    .lesson-head-position {
        @include variables.featured-title;
        color: var(--color-accent-medium);
        font-size: 3.2rem;
    }

    .lesson-head-title {
        font-family: var(--head-font), sans-serif;
        font-size: 4.8rem;
        font-weight: 700;
        text-transform: none;
        hyphens: none;
    }
}

.lesson-data-body {
    display: grid;
    gap: 3.2rem;
    font-size: 1.8rem;
    line-height: 1.5;
    padding: 3.2rem;
}
</style>
