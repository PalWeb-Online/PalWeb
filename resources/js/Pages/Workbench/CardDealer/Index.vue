<script setup>
import {route} from "ziggy-js";
import {computed, onMounted, reactive, ref, watch} from "vue";
import {useUserStore} from "../../../stores/UserStore.js";
import {useSearchStore} from "../../../stores/SearchStore.js";
import DeckItem from "../../../components/DeckItem.vue";
import ReviewQueue from "./UI/ReviewQueue.vue";
import ReviewHistory from "./UI/ReviewHistory.vue";
import SessionPreview from "./UI/SessionPreview.vue";
import ReviewProgress from "./UI/ReviewProgress.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";

const UserStore = useUserStore();
const SearchStore = useSearchStore();

const props = defineProps({
    scope: String,
    deckId: Number,
})

const scope = ref(props.scope ?? 'all');

const mode = computed(() => {
    return scope.value === 'all' ? 'all' : 'deck';
});

const isLoading = ref(false);

const allData = reactive({
    cards: [],
    termsCount: 0,
    sessionStats: null,
})

const scopeData = reactive({
    deck: null,
    cards: [],
    termsCount: 0,
    sessionStats: null,
})

const reviewHistory = ref({});

onMounted(async () => {
    const params = Object.fromEntries(new URLSearchParams(window.location.search));
    const {data} = await axios.get(route('api.card-dealer.index', params));

    scope.value = data.scope ?? scope.value;
    scopeData.deck = data.deck;
    allData.cards = data.cards;
    allData.termsCount = data.terms_count;
    allData.sessionStats = data.session_stats;
    reviewHistory.value = data.review_history;
});

const allSessionStats = computed(() => mapSessionStats(allData.sessionStats))
const scopeSessionStats = computed(() => mapSessionStats(scopeData.sessionStats))

const mapSessionStats = (stats) => ({
    ownedReviewedToday: stats?.owned_reviewed_today ?? 0,
    newReviewedToday: stats?.new_reviewed_today ?? 0,
    remainingDue: stats?.remaining_due ?? 0,
    remainingNew: stats?.remaining_new ?? 0,
    availableNew: stats?.available_new ?? 0,
    remainingReviews: stats?.remaining_reviews ?? 0,
})

const selectedScopeKey = computed(() => {
    if (mode.value !== 'deck') {
        return 'all';
    }

    if (scope.value === 'deck') {
        return `deck:${scopeData.deck?.id ?? 'none'}`;
    }

    return scope.value;
});

watch(
    selectedScopeKey,
    async (newKey, oldKey) => {
        if (newKey === oldKey) {
            return;
        }

        if (mode.value !== 'deck') {
            return;
        }

        if (scope.value === 'deck' && !scopeData.deck?.id) {
            return;
        }

        isLoading.value = true;

        try {
            await fetchScopeData();

        } finally {
            isLoading.value = false;
        }
    }
);

watch(
    () => SearchStore.data.selectedModel,
    async (newModel) => {
        if (!newModel) {
            return;
        }

        scopeData.deck = newModel;
        scope.value = 'deck';
        SearchStore.deselectModel();
    }
);

const openDeckSelector = () => {
    if (!scopeData.deck?.id) {
        SearchStore.openSearchGenie('insert', 'decks');
        return;
    }

    scope.value = 'deck';
};

const fetchScopeData = async () => {
    try {
        const payload = {
            scope: scope.value,
        };

        if (scope.value === 'deck' && scopeData.deck?.id) {
            payload.deck = scopeData.deck.id;
        }

        const response = await axios.post(route('api.card-dealer.get.cards'), payload);

        if (response.data) {
            scopeData.cards = response.data.cards;
            scopeData.termsCount = response.data.terms_count;
            scopeData.sessionStats = response.data.session_stats;
        }

    } catch (error) {
        console.error(`Error fetching Cards for ${scope.value} scope:`, error);
    }
};

const activeCards = computed(() => {
    return mode.value === 'deck' ? scopeData.cards : allData.cards
})

const activeTermsCount = computed(() => {
    return mode.value === 'deck' ? scopeData.termsCount : allData.termsCount
})

const activeSessionStats = computed(() => {
    return mode.value === 'deck' ? scopeSessionStats.value : allSessionStats.value;
})

const refreshSessionData = async () => {
    isLoading.value = true;

    try {
        if (mode.value === 'deck') {
            await fetchScopeData();
        } else {
            const {data} = await axios.get(route('api.card-dealer.index', {
                scope: scope.value,
            }));

            allData.cards = data.cards;
            allData.termsCount = data.terms_count;
            allData.sessionStats = data.session_stats;
            reviewHistory.value = data.review_history;
        }
    } finally {
        isLoading.value = false;
    }
};

const handleSessionDataRefresh = async () => {
    await refreshSessionData();
};

</script>

<template>
    <div id="app-head">
        <h1>{{ $t('pages.card-dealer.title') }}</h1>
    </div>
    <div id="app-body">
        <div class="scope-selection-container">
            <div class="featured-title m">{{ $t('card-dealer.scope') }}</div>
            <div>
                <div class="scope-button-wrapper">
                    <button class="scope-button material-symbols-rounded" :class="{selected: scope === 'all'}"
                            @click="() => {scope = 'all'}">
                        all_inclusive
                    </button>
                    <button class="scope-button material-symbols-rounded" :class="{selected: scope === 'lesson'}"
                            @click="() => {scope = 'lesson'}">
                        school
                    </button>
                    <button class="scope-button material-symbols-rounded" :class="{selected: scope === 'pinned'}"
                            @click="() => {scope = 'pinned'}">
                        keep
                    </button>
                    <button class="scope-button material-symbols-rounded" :class="{selected: scope === 'deck'}"
                            @click="openDeckSelector">
                        cards_stack
                    </button>
                </div>
                <p v-if="scope === 'all'">
                    See Cards from among <b>all Terms in the Dictionary</b>. Select this option if you're a true
                    completionist. Cards will be created in order of usage frequency (i.e. how many times the Term
                    appears in Sentences).
                </p>
                <p v-if="scope === 'lesson'">
                    Limit Cards to the Terms present across <b>all Decks you've unlocked in the Academy</b>. Select this
                    option if you're following the main course.
                </p>
                <p v-if="scope === 'pinned'">
                    Limit Cards to the Terms present across <b>all your Pinned Decks</b>. If you're curating your own
                    vocabulary
                    sets, this option is for you.
                </p>
                <p v-if="scope === 'deck'">
                    Limit Cards to the Terms present in <b>a single Deck</b> if you want to target a specific set of Terms.
                </p>
            </div>

            <template v-if="mode === 'deck' && scope === 'deck'">
                <div class="deck-preview-wrapper" v-if="scopeData.deck">
                    <DeckItem :model="scopeData.deck"/>
                    <span class="material-symbols-rounded"
                          @click="SearchStore.openSearchGenie('insert', 'decks')">cycle
                </span>
                </div>
            </template>

            <ReviewProgress v-if="!isLoading"
                            :cards="activeCards"
                            :terms_count="activeTermsCount"
            />
            <LoadingSpinner v-else/>
        </div>

        <template v-if="!isLoading">
            <SessionPreview
                v-if="!isLoading"
                :deckId="scopeData.deck?.id"
                :scope="scope"
                :active-stats="activeSessionStats"
                :all-stats="allSessionStats"
                @refresh="handleSessionDataRefresh"
            />

            <div class="score-stats-wrapper">
                <div class="score-stats-container" :class="{ disabled: !UserStore.isStudent }">
                    <div class="score-stats-container__overlay">
                        <span>{{ $t('components.score.student-required') }}</span>
                    </div>
                    <div class="score-stats-container__content">
                        <ReviewQueue :cards="activeCards"/>
                        <ReviewHistory :review_history="reviewHistory"/>
                    </div>
                </div>
            </div>
        </template>
        <LoadingSpinner v-else/>
    </div>
</template>

<style scoped lang="scss">
.scope-selection-container {
    width: min(96rem, 100%);
    display: grid;
    gap: 0.8rem;
    background: var(--color-medium-secondary);
    padding: 0.8rem;

    @media (width >= 960px) {
        border-radius: 2.4rem;
    }

    .featured-title {
        justify-self: end;
        color: white;
        margin-inline: 1.6rem;
    }

    .scope-button-wrapper {
        margin-inline: 3.2rem;
        display: flex;
        gap: 1.2rem;
    }

    .scope-button {
        color: white;
        min-width: min(12.8rem, 20%);
        border-radius: 1.6rem 1.6rem 0 0;
        padding: 0.8rem 3.2rem;

        &.selected {
            color: var(--color-dark-primary);
            background: var(--color-pastel-light);
        }
    }

    p {
        color: var(--color-dark-primary);
        background: var(--color-pastel-light);
        border-radius: 1.6rem;
        padding: 2.0rem 2.4rem;
        margin: 0;
    }
}

.deck-scope-buttons {
    width: min(96rem, 100%);
    display: flex;
    gap: 1.2rem;
    flex-wrap: wrap;
    margin-bottom: 1.6rem;
}

.deck-preview-wrapper {
    width: min(96rem, 100%);
    display: flex;
    gap: 0.4rem;
    align-items: center;

    .model-item-container {
        flex-grow: 1;
    }

    .material-symbols-rounded {
        margin-inline: 0.8rem;
        color: white;
        cursor: pointer;
    }
}
</style>
