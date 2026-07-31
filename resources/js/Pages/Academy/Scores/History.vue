<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import ScoreStats from "../../../components/ScoreStats.vue";
import AppTip from "../../../components/AppTip.vue";
import PinButton from "../../../components/PinButton.vue";
import DeckActions from "../../../components/Actions/DeckActions.vue";
import WindowSection from "../../../components/WindowSection.vue";
import Paginator from "../../../Shared/Paginator.vue";
import ModalWrapper from "../../../components/Modals/ModalWrapper.vue";
import PurgeScores from "../../../components/Modals/PurgeScores.vue";
import {nextTick, onMounted, ref, watch} from "vue";
import ScoreDetail from "../../../components/ScoreDetail.vue";
import {router} from "@inertiajs/vue3";
import DeckAnswerItem from "../../Workbench/DeckMaster/UI/DeckAnswerItem.vue";
import ActivityActions from "../../../components/Actions/ActivityActions.vue";
import DocumentBlocksRenderer from "../../../components/Blocks/Renderers/DocumentBlocksRenderer.vue";
import {useScoreManager} from "../../../composables/useScoreManager.js";
import {useI18n} from "vue-i18n";
import {useNotificationStore} from "../../../stores/NotificationStore.js";

defineOptions({
    layout: Layout
});

const props = defineProps({
    model: Object,
    scorable_type: String,
    scores: Array,
    totalCount: Number,
    selectedScore: Object,
})

const {getScoreStats} = useScoreManager();
const {t} = useI18n();
const NotificationStore = useNotificationStore();

const showPurgeScores = ref(false);

const deleteScore = async (id) => {
    if (!confirm(t('forms.notifications.delete-confirm', {model: t('actions.models.score')}))) return;

    const {data} = await axios.delete(route('scores.destroy', id));

    if (data.success) {
        NotificationStore.addNotification(t('forms.notifications.delete-success', {model: t('actions.models.score')}));

        if (props.selectedScore?.id === id) {
            router.get(route('scores.history', {
                scorable_type: props.scorable_type,
                scorable_id: props.model.id,
            }));
            return;
        }

        router.reload();
    }
}

function scrollToDetail() {
    nextTick(() => {
        const el = document.querySelector('#score-detail');
        if (el) {
            el.scrollIntoView({behavior: 'smooth', block: 'start'});
        }
    });
}

onMounted(() => {
    if (props.selectedScore) {
        scrollToDetail();
    }
});

watch(() => props.selectedScore, (newVal) => {
    if (newVal) {
        scrollToDetail();
    }
});
</script>
<template>
    <Head :title="`Academy: Score History for ${model.id}`"/>
    <div id="app-head">
        <Link :href="route('scores.index')"><h1>{{ $t('pages.scores.index.title') }}</h1></Link>
    </div>
    <div id="app-body">
        <div class="window-container">
            <div class="window-header">
                <Link :href="route('scores.index')" class="material-symbols-rounded">home</Link>
                <div class="window-header-url">www.palweb.app/academy/scores/history/{{ '{' + scorable_type + '}' }}
                </div>
            </div>
            <div class="window-section-head">
                <h1>{{ $t(`components.${scorable_type}.title`) }}</h1>
                <PinButton :modelType="scorable_type" :model="model"/>
                <DeckActions v-if="scorable_type === 'deck'" :model="model"/>
                <ActivityActions v-if="scorable_type === 'activity'" :model="model"/>
            </div>
            <div class="window-content-head">
                <div class="window-content-head-title">{{ scorable_type === 'deck' ? model.name : model.title }}</div>
            </div>
            <WindowSection>
                <template #title>
                    <h2>{{ $t('components.common.sections.stats') }}</h2>
                </template>
                <template #content>
                    <ScoreStats :model="model"/>
                </template>
            </WindowSection>

            <template v-if="selectedScore">
                <div class="window-section-head" id="score-detail">
                    <Link
                        :href="route('scores.history', { scorable_type: selectedScore?.scorable_type, scorable_id: selectedScore?.scorable_id })"
                        class="material-symbols-rounded" preserve-scroll preserve-state>
                        arrow_back
                    </Link>
                    <h2>{{ $t('components.score.sections.detail') }}</h2>
                    <button @click="deleteScore(selectedScore?.id)" class="material-symbols-rounded">delete</button>
                </div>
                <ScoreDetail :score="selectedScore">
                    <div class="quiz-answer-array" v-if="scorable_type === 'deck'">
                        <DeckAnswerItem v-for="(exercise, index) in selectedScore.results" :key="index"
                                        :exercise="exercise"
                        />
                    </div>
                </ScoreDetail>
            </template>
            <template v-else>
                <div class="window-section-head">
                    <h2>{{ $t('components.score.sections.history') }}</h2>
                    <button v-if="totalCount > 0" @click="showPurgeScores = true" class="material-symbols-rounded">
                        delete_sweep
                    </button>
                </div>
                <AppTip>
                    <p v-if="totalCount > 0">
                        {{
                            $t('pages.scores.history.messages.displaying-all', {
                                count: totalCount,
                                model: $t(`components.${scorable_type}.title`)
                            })
                        }}
                    </p>
                    <p v-else>
                        {{
                            $t('pages.scores.history.messages.no-scores', {
                                model: $t(`components.${scorable_type}.title`)
                            })
                        }}
                    </p>
                </AppTip>
                <template v-if="totalCount > 0">
                    <div class="score-item-wrapper" v-for="score in scores.data">
                        <Link
                            :href="route('scores.history', { scorable_type: score.scorable_type, scorable_id: score.scorable_id, score: score.id })"
                            class="score-item" preserve-scroll preserve-state>
                            <div style="text-transform: capitalize">{{ $t(`models.${score.settings.quizType}`) ?? $t('components.activity.title') }}</div>
                            <div>{{ getScoreStats(score).formatted }}
                                ({{ getScoreStats(score).correct }}/{{ getScoreStats(score).total }})
                            </div>
                            <div style="font-size: 1.2rem">{{ score.created_at }}</div>
                        </Link>
                        <button @click="deleteScore(score.id)" class="material-symbols-rounded">delete</button>
                    </div>
                    <Paginator :links="scores.meta.links"/>
                </template>
            </template>
        </div>

        <div v-if="selectedScore && scorable_type === 'activity'" class="activity-blocks-wrapper">
            <DocumentBlocksRenderer :blocks="selectedScore.results"/>
        </div>
    </div>

    <ModalWrapper v-model="showPurgeScores">
        <PurgeScores :scorable_type="scorable_type" :scorable_id="model.id"/>
    </ModalWrapper>
</template>

<style scoped lang="scss">
.score-item-wrapper {
    display: flex;
    align-items: center;
    gap: 1.2rem;
    margin: 1.6rem;

    .material-symbols-rounded {
        font-size: 2.0rem;
        color: var(--color-dark-primary);
    }
}

.score-item {
    flex-grow: 1;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    align-items: center;
    background: var(--color-pastel-light);
    color: var(--color-dark-primary);
    border-radius: 0.8rem;
    padding: 1.2rem 2.4rem;
    font-family: var(--body-font);
    font-weight: 700;
    font-size: 1.6rem;

    &:hover {
        background: var(--color-pastel-medium);
    }

    & > *:last-child {
        justify-self: end;
    }
}
</style>
