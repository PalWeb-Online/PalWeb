<script setup>
import {onBeforeUnmount, onMounted, ref} from "vue";
import Layout from "../../../Shared/Layout.vue";
import AppButton from "../../../components/AppButton.vue";
import DeckFlashcard from "../../../components/DeckFlashcard.vue";
import {route} from "ziggy-js";
import {router} from "@inertiajs/vue3";
import AppTip from "../../../components/AppTip.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import QuizzerWindow from "./UI/QuizzerWindow.vue";
import ScoreStats from "../../../components/ScoreStats.vue";
import {useDeckStudyStore} from "./Stores/DeckStudyStore.js";

const DeckStudyStore = useDeckStudyStore();

const props = defineProps({
    mode: {type: String, default: 'build'},
});

const mode = ref(props.mode);
const decks = ref([]);
const selectedDeck = ref(null);

const toggleMode = async () => {
    selectedDeck.value = null;
    DeckStudyStore.reset();
    mode.value = mode.value === 'build' ? 'study' : 'build';
    await fetchDecks(mode.value);
}

const isLoading = ref(false);

const fetchDecks = async (mode) => {
    isLoading.value = true;
    decks.value = [];

    try {
        const response = await axios.get(route('deck-master.get-decks'), {
            params: {mode}
        });

        decks.value = response.data.decks;

    } catch (error) {
        console.error("Failed to fetch decks", error);
    }

    isLoading.value = false;
};

const toggleSelectDeck = (index) => {
    if (mode.value === 'build') {
        selectedDeck.value =
            selectedDeck.value?.id === decks.value[index].id
                ? null
                : decks.value[index];
    } else {
        DeckStudyStore.data.deck =
            DeckStudyStore.data.deck?.id === decks.value[index].id
                ? null
                : decks.value[index];
    }
};

const toBuild = () => {
    selectedDeck.value?.id
        ? router.get(route('deck-master.build', selectedDeck.value?.id))
        : router.get(route('deck-master.build'))
};

const toStudy = () => {
    if (DeckStudyStore.data.deck.id) {
        router.get(route('deck-master.study', DeckStudyStore.data.deck.id));
    }
};

onMounted(async () => {
    await fetchDecks(props.mode);
    selectedDeck.value = props.deck;

    DeckStudyStore.reset();
    DeckStudyStore.data.step = 'select';
});

onBeforeUnmount(() => {
    DeckStudyStore.reset();
});

defineOptions({
    layout: Layout
});
</script>

<template>
    <Head title="Deck Master"/>
    <div id="app-head">
        <h1>Deck Master</h1>
        <div id="app-nav">
            <div @click="toggleMode" id="app-mode-toggle" :class="mode">
                <div class="app-mode-toggle-slider">{{ mode }}</div>
            </div>
        </div>
    </div>

    <div id="app-body" :class="{ 'dm-mode-study': mode === 'study' }">
        <div id="dm-select">
            <QuizzerWindow v-if="mode === 'study'">
                <ScoreStats :model="DeckStudyStore.data.deck"/>
                <div v-if="DeckStudyStore.data.deck" class="window-footer">
                    <button @click="toStudy">
                        Select Deck
                    </button>
                </div>
            </QuizzerWindow>

            <div class="app-nav-interact" v-if="mode === 'build'">
                <div class="app-nav-interact-buttons">
                    <AppButton @click="toBuild" :label="selectedDeck ? 'Edit' : 'New'"/>
                </div>
            </div>

            <div v-if="!isLoading && decks.length" class="deck-item-grid">
                <DeckFlashcard v-for="(deck, index) in decks" :key="deck.id" :model="deck"
                               :disabled="mode === 'study' && !deck.terms.length"
                               :active="selectedDeck?.id === deck.id || DeckStudyStore.data.deck?.id === deck.id"
                               @flip="toggleSelectDeck(index)"
                />
            </div>
            <div v-else-if="!isLoading && !decks.length" class="deck-item-grid">
                <AppTip>
                    <p v-if="mode === 'build'">It looks like you haven't created any Decks yet. Click <b>New</b> to get
                        started!</p>
                    <p v-if="mode === 'study'">It looks like you haven't pinned any Decks yet. Watch this space.</p>
                </AppTip>
            </div>
            <LoadingSpinner v-show="isLoading"/>
        </div>
    </div>
</template>
