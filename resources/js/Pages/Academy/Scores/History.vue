<script setup>
import ScoreStats from '../../../components/ScoreStats.vue';
import AppTip from '../../../components/AppTip.vue';
import PinButton from '../../../components/PinButton.vue';
import DeckActions from '../../../components/Actions/DeckActions.vue';
import WindowSection from '../../../components/WindowSection.vue';
import Paginator from '../../../Shared/Paginator.vue';
import ModalWrapper from '../../../components/Modals/ModalWrapper.vue';
import PurgeScores from '../../../components/Modals/PurgeScores.vue';
import {nextTick, onMounted, ref, watch} from 'vue';
import ScoreDetail from '../../../components/ScoreDetail.vue';
import {useRoute, useRouter} from 'vue-router';
import DeckAnswerItem from '../../Workbench/DeckMaster/UI/DeckAnswerItem.vue';
import ActivityActions from '../../../components/Actions/ActivityActions.vue';
import DocumentBlocksRenderer from '../../../components/Blocks/Renderers/DocumentBlocksRenderer.vue';
import {useScoreManager} from '../../../composables/useScoreManager.js';
import {useI18n} from 'vue-i18n';
import {useNotifier} from '../../../composables/useNotifier.js';
import {usePaginator} from '../../../composables/usePaginator.js';
import PaginationPage from '../../../components/PaginationPage.vue';
import LoadingWrapper from '../../../Shared/LoadingWrapper.vue';

const route = useRoute();
const currentQuery = () => ({ ...route.query });

const props = defineProps({
    scorable_type: String,
    scorable_id: Number,
    scoreId: {
        type: Number,
        default: null,
    },
});

const emptyScores = () => ({
    data: [],
    meta: {
        links: [],
        current_page: 1,
        last_page: 1,
        total: 0,
    },
});

const { getScoreStats } = useScoreManager();
const { t } = useI18n();
const router = useRouter();
const Notifier = useNotifier();

const showPurgeScores = ref(false);
const model = ref(null);
const scores = ref(emptyScores());
const totalCount = ref(0);
const selectedScore = ref(null);
const isLoadingScore = ref(false);

const fetchScoreable = async (params = {}) => {
    isLoadingScore.value = true;

    const { data } = await axios.get(`/api/scores/history/${props.scorable_type}/${props.scorable_id}`, { params });

    model.value = data.model;
    scores.value = data.scores ?? emptyScores();
    totalCount.value = data.totalCount;
    selectedScore.value = data.selectedScore;

    isLoadingScore.value = false;
};

const fetchModelScores = async (params = {}) => {
    const response = await axios.get(`/api/scores/history/${props.scorable_type}/${props.scorable_id}`, { params });
    scores.value = response.data.scores ?? emptyScores();
    return scores.value;
};

const fetchScorePage = async (params = {}) => {
    isLoadingPaginator.value = true;

    try {
        await fetchModelScores(params);
    } finally {
        isLoadingPaginator.value = false;
    }
};

const {
    currentPage,
    pageNumbers,
    goToPage,
    updatePagination,
    isLoadingPaginator,
} = usePaginator(fetchScorePage);

const deleteScore = async (id) => {
    if (!confirm(t('forms.notifications.delete-confirm', { model: t('actions.models.score') }))) return;

    const { data } = await axios.delete(`/api/scores/${id}`);

    if (data.success) {
        Notifier.notify({
            key: 'forms.notifications.delete-success',
            params: {
                model: t('actions.models.score'),
            },
        });

        if (selectedScore.value?.id === id) {
            router.push({
                name: 'scores.history',
                params: {
                    scorable_type: props.scorable_type,
                    scorable_id: model.value.id,
                },
            });
            return;
        }

        await fetchScoreable(currentQuery());
    }
};

function scrollToDetail() {
    nextTick(() => {
        const el = document.querySelector('#score-detail');
        if (el) {
            el.scrollIntoView({
                behavior: 'smooth',
                block: 'start',
            });
        }
    });
}

onMounted(async () => {
    await fetchScoreable(currentQuery());

    if (selectedScore.value) {
        scrollToDetail();
    }
});

watch(() => scores.value.meta, updatePagination);

watch(
    () => [props.scorable_type, props.scorable_id, props.scoreId, route.query.page],
    ([scorableType, scorableId, scoreId], [oldScorableType, oldScorableId, oldScoreId]) => {
        if (
            scorableType !== oldScorableType ||
            scorableId !== oldScorableId ||
            scoreId !== oldScoreId
        ) {
            fetchScoreable(currentQuery());
            return;
        }

        if (!scoreId) {
            fetchScorePage(currentQuery());
        }
    },
);

watch(selectedScore, (newVal) => {
    if (newVal) {
        scrollToDetail();
    }
});
</script>
<template>
    <div id="app-head">
        <RouterLink :to="{ name: 'scores.index' }"><h1>{{ $t('pages.scores.index.title') }}</h1></RouterLink>
    </div>
    <LoadingWrapper :is-loading="isLoadingScore">
        <div id="app-body">
            <div class="window-container">
                <div class="window-header">
                    <RouterLink :to="{ name: 'scores.index' }" class="material-symbols-rounded">home</RouterLink>
                    <div class="window-header-url">www.palweb.app/academy/scores/history/{{ '{' + scorable_type + '}' }}
                    </div>
                </div>
                <template v-if="model">
                    <div class="window-section-head">
                        <h1>{{ $t(`components.${scorable_type}.title`) }}</h1>
                        <PinButton :modelType="scorable_type" :model="model"/>
                        <DeckActions v-if="scorable_type === 'deck'" :model="model"/>
                        <ActivityActions v-if="scorable_type === 'activity'" :model="model"/>
                    </div>
                    <div class="window-content-head">
                        <div class="window-content-head-title">{{
                                scorable_type === 'deck' ? model.name : model.title
                            }}
                        </div>
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
                            <RouterLink class="material-symbols-rounded"
                                        :to="{
                            name: 'scores.history',
                            params: {
                                scorable_type: selectedScore?.scorable_type,
                                scorable_id: selectedScore?.scorable_id,
                            },
                        }">
                                arrow_back
                            </RouterLink>
                            <h2>{{ $t('components.score.sections.detail') }}</h2>
                            <button @click="deleteScore(selectedScore?.id)" class="material-symbols-rounded">delete
                            </button>
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
                            <button v-if="totalCount > 0" @click="showPurgeScores = true"
                                    class="material-symbols-rounded">
                                delete_sweep
                            </button>
                        </div>
                        <AppTip>
                            <p v-if="totalCount > 0">
                                {{
                                    $t('pages.scores.history.messages.displaying-all', {
                                        count: totalCount,
                                        model: $t(`components.${scorable_type}.title`),
                                    })
                                }}
                            </p>
                            <p v-else>
                                {{
                                    $t('pages.scores.history.messages.no-scores', {
                                        model: $t(`components.${scorable_type}.title`),
                                    })
                                }}
                            </p>
                        </AppTip>
                        <template v-if="totalCount > 0">
                            <PaginationPage :is-loading="isLoadingPaginator">
                                <div class="score-item-wrapper" v-for="score in scores.data" :key="score.id">
                                    <RouterLink class="score-item"
                                                :to="{
                                    name: 'scores.history',
                                    params: {
                                        scorable_type: score.scorable_type,
                                        scorable_id: score.scorable_id,
                                    },
                                    query: {score: score.id},
                                }">
                                        <div style="text-transform: capitalize">
                                            {{
                                                score.settings.quizType ? $t(`models.${score.settings.quizType}`) : $t('components.activity.title')
                                            }}
                                        </div>
                                        <div>{{ getScoreStats(score).formatted }}
                                            ({{ getScoreStats(score).correct }}/{{ getScoreStats(score).total }})
                                        </div>
                                        <div style="font-size: 1.2rem">{{ score.created_at }}</div>
                                    </RouterLink>
                                    <button @click="deleteScore(score.id)" class="material-symbols-rounded">delete
                                    </button>
                                </div>
                            </PaginationPage>
                            <Paginator
                                :pageNumbers="pageNumbers"
                                :currentPage="currentPage"
                                :goToPage="goToPage"
                            />
                        </template>
                    </template>
                </template>
            </div>

            <div v-if="selectedScore && scorable_type === 'activity'" class="activity-blocks-wrapper">
                <DocumentBlocksRenderer :blocks="selectedScore.results"/>
            </div>
        </div>

        <ModalWrapper v-model="showPurgeScores">
            <PurgeScores v-if="model" :scorable_type="scorable_type" :scorable_id="model.id"/>
        </ModalWrapper>
    </LoadingWrapper>
</template>

<style scoped lang="scss">
.score-item-wrapper {
    display: flex;
    align-items: center;
    gap: 1.2rem;

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
