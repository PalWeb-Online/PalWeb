<script setup>
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {computed, onMounted, reactive, ref, watch} from "vue";
import Layout from "../../../Shared/Layout.vue";
import {useUserStore} from "../../../stores/UserStore.js";
import AppButton from "../../../components/AppButton.vue";
import {useSearchStore} from "../../../stores/SearchStore.js";
import DeckItem from "../../../components/DeckItem.vue";
import ReviewQueue from "./UI/ReviewQueue.vue";
import ReviewHistory from "./UI/ReviewHistory.vue";
import SessionPreview from "./UI/SessionPreview.vue";
import ReviewProgress from "./UI/ReviewProgress.vue";

defineOptions({
    layout: Layout
});

const UserStore = useUserStore();
const SearchStore = useSearchStore();

const props = defineProps({
    deck: Object,
    cards: Array,
    terms_count: Number,
    review_history: Object,
    session_stats: Object,
})

const scope = ref('all');

const toggleScope = async () => {
    scope.value = scope.value === 'all' ? 'deck' : 'all';
}

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

onMounted(async () => {
    if (props.deck) {
        await fetchDeckData(props.deck.id);
        scope.value = 'deck';
    }

    watch(
        () => SearchStore.data.selectedModel,
        (newModel) => {
            if (newModel) {
                deckData.deck = newModel;
                fetchDeckData(newModel.id)
                SearchStore.deselectModel();
            }
        }
    );
});

const fetchDeckData = async (id) => {
    try {
        const response = await axios.post(route('card-dealer.get.deck', id));

        if (response.data) {
            deckData.cards = response.data.cards;
            deckData.termsCount = response.data.terms_count;
            deckData.sessionStats = response.data.session_stats;
        }
    } catch (error) {
        console.error(`Error fetching Deck with ID ${id}:`, error);
    }
};

const allData = reactive({
    cards: props.cards,
    termsCount: props.terms_count,
    sessionStats: props.session_stats,
})

const deckData = reactive({
    deck: props.deck,
    cards: [],
    termsCount: 0,
    sessionStats: null,
})

const activeCards = computed(() => {
    return scope.value === 'deck' ? deckData.cards : allData.cards
})

const activeTermsCount = computed(() => {
    return scope.value === 'deck' ? deckData.termsCount : allData.termsCount
})

const allSessionStats = computed(() => mapSessionStats(allData.sessionStats))
const deckSessionStats = computed(() => mapSessionStats(deckData.sessionStats))

const activeSessionStats = computed(() => {
    return scope.value === 'deck' ? deckSessionStats.value : allSessionStats.value;
})

const mapSessionStats = (stats) => ({
    ownedReviewedToday: stats?.owned_reviewed_today ?? 0,
    newReviewedToday: stats?.new_reviewed_today ?? 0,
    remainingDue: stats?.remaining_due ?? 0,
    remainingNew: stats?.remaining_new ?? 0,
    availableNew: stats?.available_new ?? 0,
    remainingReviews: stats?.remaining_reviews ?? 0,
})

const refreshSessionData = async () => {
    router.reload({
        only: ['cards', 'session_stats'],
        preserveScroll: true,
        preserveState: true,
    });

    if (props.deck) {
        await fetchDeckData(props.deck.id);
    }
};

const handleSessionDataRefresh = async () => {
    await refreshSessionData();
};
</script>

<template>
    <Head title="Card Dealer"/>
    <div id="app-head">
        <h1>Card Dealer</h1>
        <div @click="toggleScope" id="app-mode-toggle">
            <div class="app-mode-toggle-slider" :class="{active: scope === 'deck'}">{{ scope }}</div>
        </div>
    </div>
    <div id="app-body">
        <AppButton v-if="scope === 'deck' && !deckData.deck"
                   @click="SearchStore.openSearchGenie('insert', 'decks')" label="select deck"/>
        <SessionPreview
            :deckId="deckData.deck?.id"
            :scope="scope"
            :active-stats="activeSessionStats"
            :all-stats="allSessionStats"
            @refresh="handleSessionDataRefresh"
        />

        <div class="deck-preview-wrapper" v-if="scope === 'deck' && deckData.deck">
            <DeckItem :model="deckData.deck"/>
            <span class="material-symbols-rounded"
                  @click="SearchStore.openSearchGenie('insert', 'decks')">cycle
            </span>
        </div>
        <ReviewProgress
            :cards="activeCards"
            :terms_count="activeTermsCount"
        />
        <div class="score-stats-container" style="width: min(96rem, 100%)"
             :class="{ disabled: !UserStore.isStudent }">
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

<style scoped lang="scss">
.deck-preview-wrapper {
    width: min(96rem, 100%);
    display: flex;
    gap: 1.6rem;
    align-items: center;

    .model-item-container {
        flex-grow: 1;
    }

    .material-symbols-rounded {
        color: var(--color-dark-primary);
        cursor: pointer;
    }
}
</style>
