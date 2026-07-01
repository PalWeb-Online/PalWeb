<script setup>
import Layout from "../../../Shared/Layout.vue";
import {computed, onMounted, watch} from "vue";
import Results from "./Pages/Results.vue";
import {createActivitySession, provideActivitySession} from "../../../composables/activities/useActivitySession.js";
import DocumentBlocksRenderer from "../../../components/Blocks/Renderers/DocumentBlocksRenderer.vue";
import NavGuard from "../../../components/Modals/NavGuard.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import {route} from "ziggy-js";
import {useNavGuard} from "../../../composables/NavGuard.js";
import {useActivityViewer} from "../../../composables/activities/useActivityViewer.js";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import AppTip from "../../../components/AppTip.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    activityId: {
        type: Number,
        required: true,
    },
})

const {
    activityNotFound,
    isLoadingActivity,
    loadActivity,
    reloadActivity,
} = useActivityViewer();

const ActivitySession = provideActivitySession(createActivitySession());

const openActivity = async (loader) => {
    ActivitySession.reset();
    ActivitySession.activity = await loader(props.activityId);

    if (ActivitySession.activity) {
        ActivitySession.startActivity();
    }
};

onMounted(() => {
    void openActivity(loadActivity);
});

watch(() => props.activityId, () => {
    void openActivity(reloadActivity);
});

const hasNavigationGuard = computed(() => !ActivitySession.isViewingResults);
const {showAlert, handleConfirm, handleCancel} = useNavGuard(hasNavigationGuard);

const isValidRequest = computed(() => {
    if (!ActivitySession.exercises.length) return;

    return !ActivitySession.exercises.some(e => {
        if (e.response === null || e.response === undefined) return true;

        if (typeof e.response === 'string' || Array.isArray(e.response)) {
            return e.response.length === 0;
        }

        return false;
    })
});
</script>

<template>
    <Head :title="ActivitySession.activity?.title ?? 'Activity'"/>

    <LoadingSpinner v-if="isLoadingActivity"/>
    <template v-else-if="activityNotFound">
        <AppTip>
            <p>Sorry, but the requested Activity could not be found.</p>
        </AppTip>
    </template>
    <template v-else-if="ActivitySession.activity">
        <div class="activity-head" v-if="!ActivitySession.isViewingResults">
            <Link class="feature-callout" style="justify-self: center"
                  :href="route('lessons.show', ActivitySession.activity.lesson.global_position)">
                Exit to Lesson
            </Link>
            <h1>activity</h1>
        </div>
        <div id="app-body">
            <div class="activity-container" v-if="!ActivitySession.isViewingResults">
                <div v-if="!ActivitySession.isLoadingSession" class="activity-blocks-wrapper">
                    <DocumentBlocksRenderer :blocks="ActivitySession.activity.document.blocks"/>
                </div>
                <button class="material-symbols-rounded" :disabled="!isValidRequest"
                        @click="ActivitySession.submitActivity">
                    check
                </button>
            </div>

            <Results v-if="ActivitySession.isViewingResults"/>
        </div>
    </template>

    <ModalWrapper v-model="showAlert">
        <NavGuard
            message="You haven't finished the Activity yet. Are you sure you want to leave this page? Your progress will not be saved."
            @confirm="handleConfirm"
            @cancel="handleCancel"
        />
    </ModalWrapper>
</template>

<style scoped lang="scss">
.activity-head {
    display: grid;
    justify-items: center;
    gap: 2.4rem;
    background: var(--color-accent-light);
    padding-block: 3.2rem;

    h1 {
        margin: 0;
        font-family: var(--display-font), serif;
        font-weight: 400;
        font-size: 7.2rem;
        color: var(--color-dark-primary);
        line-height: 0.75;
        padding-block-end: 1.2rem;

        @media (width >= 960px) {
            font-size: 9.6rem;
        }
    }
}
</style>
