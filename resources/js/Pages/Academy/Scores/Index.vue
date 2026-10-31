<script setup>
import DeckItem from "../../../components/DeckItem.vue";
import AppTip from "../../../components/AppTip.vue";
import Paginator from "../../../Shared/Paginator.vue";
import ActivityItem from "../../../components/ActivityItem.vue";
import {onMounted, ref, watch} from "vue";
import {usePaginator} from "../../../composables/usePaginator.js";
import PaginationPage from "../../../components/PaginationPage.vue";
import {useRoute} from "vue-router";

const route = useRoute();
const currentQuery = () => ({ ...route.query });

const emptyScoredDecks = () => ({
    data: [],
    meta: {
        links: [],
        current_page: 1,
        last_page: 1,
        total: 0,
    },
});

const mode = ref('decks');
const scoredLessonModels = ref({});
const latestScoredDecks = ref(emptyScoredDecks());
const totalCount = ref(0);

const fetchScores = async (params = {}) => {
    const {data} = await axios.get('/api/scores', {params});

    scoredLessonModels.value = data.scoredLessonModels;
    latestScoredDecks.value = data.latestScoredDecks ?? emptyScoredDecks();
    totalCount.value = data.totalCount;
};

const fetchScoredDeckPage = async (params = {}) => {
    isLoadingPaginator.value = true;

    try {
        await fetchScores(params);
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
} = usePaginator(fetchScoredDeckPage);

onMounted(async () => {
    await fetchScores(currentQuery());
});

watch(() => latestScoredDecks.value.meta, updatePagination);

watch(() => route.query.page, () => {
    fetchScoredDeckPage(currentQuery());
});
</script>
<template>
    <div id="app-head">
        <RouterLink :to="{ name: 'scores.index' }"><h1>{{ $t('pages.scores.index.title') }}</h1></RouterLink>
    </div>
    <div id="app-body">
        <div class="app-body-section">
            <div class="sm-mode-select">
                <button class="featured-title l" :class="{'active': mode === 'decks'}"
                        @click="mode = 'decks'">dck
                </button>
                <button class="featured-title l" :class="{'active': mode === 'lessons'}"
                        @click="mode = 'lessons'">lsn
                </button>
            </div>
            <template v-if="mode === 'decks'">
                <AppTip>
                    <p>{{ $t('pages.scores.index.messages.deck-list') }}</p>
                </AppTip>

                <div class="featured-title m" style="text-transform: none">{{ $t('pages.scores.index.latest-scored') }}</div>
                <AppTip v-if="!totalCount">
                    <p>{{ $t('pages.scores.index.messages.no-decks') }}</p>
                </AppTip>
                <template v-else>
                    <PaginationPage :is-loading="isLoadingPaginator">
                        <DeckItem v-for="deck in latestScoredDecks.data" :model="deck" :key="deck.id" target="academy"/>
                    </PaginationPage>
                    <Paginator
                        :pageNumbers="pageNumbers"
                        :currentPage="currentPage"
                        :goToPage="goToPage"
                    />
                </template>
            </template>
            <template v-if="mode === 'lessons'">
                <AppTip v-if="Object.keys(scoredLessonModels).length === 0">
                    <p>You don't have any Scores for any Lesson Decks or Activities. Have you started the Lessons in the
                        Academy yet? If not, <RouterLink :to="{ name: 'units.index' }">click here</RouterLink> to get started!
                    </p>
                </AppTip>
                <template v-else v-for="(lesson, slug) in scoredLessonModels">
                    <div class="featured-title m">{{ $t('lesson.key-index', {index: slug}) }}</div>
                    <div class="model-list">
                        <DeckItem v-if="lesson.deck" :model="lesson.deck" target="academy"/>
                        <ActivityItem v-if="lesson.activity" :model="lesson.activity" target="academy"/>
                    </div>
                </template>
            </template>
        </div>
    </div>
</template>
