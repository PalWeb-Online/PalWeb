<script setup>
import {computed, onMounted, watch} from 'vue';
import Results from './Pages/Results.vue';
import {createActivitySession, provideActivitySession} from '../../../composables/activities/useActivitySession.js';
import DocumentBlocksRenderer from '../../../components/Blocks/Renderers/DocumentBlocksRenderer.vue';
import NavGuard from '../../../components/Modals/NavGuard.vue';
import ModalWrapper from '../../../components/Modals/ModalWrapper.vue';
import {useNavGuard} from '../../../composables/NavGuard.js';
import {useActivityViewer} from '../../../composables/activities/useActivityViewer.js';
import LoadingWrapper from '../../../Shared/LoadingWrapper.vue';

const props = defineProps({
    activityId: {
        type: Number,
        required: true,
    },
});

const {
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

const hasNavigationGuard = computed(() => {
    return !ActivitySession.isViewingResults;
});
const {
    showAlert,
    handleConfirm,
    handleCancel,
} = useNavGuard(hasNavigationGuard);

const isValidRequest = computed(() => {
    if (!ActivitySession.exercises.length) return;

    return !ActivitySession.exercises.some(e => {
        if (e.response === null || e.response === undefined) return true;

        if (typeof e.response === 'string' || Array.isArray(e.response)) {
            return e.response.length === 0;
        }

        return false;
    });
});
</script>

<template>
    <LoadingWrapper :is-loading="isLoadingActivity || !ActivitySession.activity">
        <div id="app-head" class="activity-head" v-if="!ActivitySession.isViewingResults">
            <RouterLink class="feature-callout" style="justify-self: center"
                        :to="{ name: 'lessons.show', params: { lesson: ActivitySession.activity.lesson.global_position } }">
                {{ $t('components.activity.exit') }}
            </RouterLink>
            <h1>{{ $t('components.activity.title') }}</h1>
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

        <ModalWrapper v-model="showAlert">
            <NavGuard
                :message="$t('modals.nav-guard.messages.unfinished-activity')"
                @confirm="handleConfirm"
                @cancel="handleCancel"
            />
        </ModalWrapper>
    </LoadingWrapper>
</template>

<style scoped lang="scss">
#app-container.academy #app-head.activity-head {
    gap: 3.2rem;
    padding-block: 3.2rem;
    background: var(--color-accent-light);
}
</style>
