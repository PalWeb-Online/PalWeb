<script setup>
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {computed, onMounted, reactive, ref, watch} from "vue";
import Layout from "../../../Shared/Layout.vue";
import {useUserStore} from "../../../stores/UserStore.js";
import {useSearchStore} from "../../../stores/SearchStore.js";
import DeckItem from "../../../components/DeckItem.vue";
import ReviewQueue from "./UI/ReviewQueue.vue";
import ReviewHistory from "./UI/ReviewHistory.vue";
import SessionPreview from "./UI/SessionPreview.vue";
import ReviewProgress from "./UI/ReviewProgress.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";

defineOptions({
    layout: Layout
});

const UserStore = useUserStore();
const SearchStore = useSearchStore();

const props = defineProps({
    scope: String,
    deck: Object,
    cards: Array,
    terms_count: Number,
    review_history: Object,
    session_stats: Object,
})

const scope = ref(props.scope ?? 'all');

const mode = computed(() => {
    return scope.value === 'all' ? 'all' : 'deck';
});

const isLoading = ref(false);

const allData = reactive({
    cards: props.cards,
    termsCount: props.terms_count,
    sessionStats: props.session_stats,
})

const scopeData = reactive({
    deck: props.deck,
    cards: [],
    termsCount: 0,
    sessionStats: null,
})

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

watch(
    () => ({
        cards: props.cards,
        sessionStats: props.session_stats,
    }),
    ({cards, sessionStats}) => {
        allData.cards = cards;
        allData.sessionStats = sessionStats;
    },
    {deep: false}
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

        const response = await axios.post(route('card-dealer.get.cards'), payload);

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

    router.reload({
        only: ['cards', 'session_stats'],
        preserveScroll: true,
        preserveState: true,
    });

    if (mode.value === 'deck') {
        await fetchScopeData();
    }

    isLoading.value = false;
};

const handleSessionDataRefresh = async () => {
    await refreshSessionData();
};

onMounted(async () => {
    if (props.deck) {
        scopeData.deck = props.deck;
        scope.value = 'deck';
        await fetchScopeData();
    }
});
</script>

<template>
    <Head title="Card Dealer"/>
    <div id="app-head">
        <h1>Card Dealer</h1>
    </div>
    <div id="app-body">
        <div class="scope-selection-container">
            <div class="featured-title m">scope</div>
            <div>
                <div class="scope-button-wrapper">
                    <button class="scope-button" :class="{selected: scope === 'all'}"
                            @click="() => {scope = 'all'}">
                        all
                    </button>
                    <button class="scope-button" :class="{selected: scope === 'lesson'}"
                            @click="() => {scope = 'lesson'}">
                        academy
                    </button>
                    <button class="scope-button" :class="{selected: scope === 'pinned'}"
                            @click="() => {scope = 'pinned'}">
                        pins
                    </button>
                    <button class="scope-button" :class="{selected: scope === 'deck'}"
                            @click="openDeckSelector">
                        deck
                    </button>
                </div>
                <p v-if="scope === 'all'">
                    See Cards from among all Terms in the Dictionary. Select this option if you're a true completionist.
                    Cards will be created in order of usage frequency (i.e. how many times the Term appears in
                    Sentences).
                </p>
                <p v-if="scope === 'lesson'">
                    Limit Cards to the Terms present across all Decks you've unlocked in the Academy. Select this option
                    if
                    you're following the main course.
                </p>
                <p v-if="scope === 'pinned'">
                    Limit Cards to the Terms present across all your Pinned Decks. If you're curating your own
                    vocabulary
                    sets, this option is for you.
                </p>
                <p v-if="scope === 'deck'">
                    Limit Cards to the Terms present in a given Deck if you want to target a specific set of Terms.
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
                        <span>You must be a Student to enable Scores.</span>
                    </div>
                    <div class="score-stats-container__content">
                        <ReviewQueue :cards="activeCards"/>
                        <ReviewHistory :review_history="review_history"/>
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
    border-radius: 2.4rem;
    background: var(--color-medium-secondary);
    padding: 0.8rem;

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
        font-size: 2.0rem;
        font-weight: 700;
        min-width: 12.8rem;
        border-radius: 1.6rem 1.6rem 0 0;
        padding: 0.4rem 3.2rem;
        font-family: var(--head-font), sans-serif;

        &.selected {
            color: var(--color-dark-primary);
            background: var(--color-pastel-light);
        }
    }

    p {
        //width: 100%;
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
