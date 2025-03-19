<script setup>
import {onMounted, ref} from "vue";
import Layout from "../../../Shared/Layout.vue";
import Build from "./pages/Build.vue";
import Study from "./pages/Study.vue";
import AppButton from "../../../components/AppButton.vue";
import DeckFlashcard from "../../../components/DeckFlashcard.vue";
import {route} from "ziggy-js";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    mode: {type: String, default: 'build'},
    step: {type: String, default: 'select'},
    deck: Object,
    terms: Array,
});

const mode = ref(props.mode);
const decks = ref([]);
const terms = ref(props.terms);
const selectedDeck = ref(props.deck);

const toggleMode = async () => {
    selectedDeck.value = null;
    mode.value = mode.value === 'build' ? 'study' : 'build';
    await fetchDecks(mode.value);
}

const isLoading = ref(false);

const fetchDecks = async (mode) => {
    isLoading.value = true;

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
    if (selectedDeck.value?.id === decks.value[index].id) {
        selectedDeck.value = null;

    } else {
        selectedDeck.value = decks.value[index];
    }
}

const toBuild = () => {
    selectedDeck.value?.id
        ? router.get(route('deck-master.build', selectedDeck.value?.id))
        : router.get(route('deck-master.build'))
};

const toStudy = () => {
    selectedDeck.value?.id
    && router.get(route('deck-master.study', selectedDeck.value?.id));
};

onMounted(async () => {
    await fetchDecks(props.mode);
    selectedDeck.value = props.deck;
});

defineOptions({
    layout: Layout
});
</script>

<template>
    <Head title="Deck Master"/>
    <div id="app-head">
        <h1>Deck Master</h1>
        <div v-if="step === 'select'" id="app-nav">
            <div @click="toggleMode" id="app-mode-toggle" :class="mode">
                <div class="app-mode-toggle-slider">{{ mode }}</div>
            </div>
        </div>
    </div>

    <div id="app-body" :class="{ 'dm-mode-study': mode === 'study' }">
        <div id="dm-select" v-if="step === 'select'">
            <div class="app-nav-interact">
                <div class="app-nav-interact-buttons">
                    <AppButton v-if="mode === 'build'" @click="toBuild"
                               :label="selectedDeck ? 'Edit' : 'New'"/>
                    <AppButton v-if="mode === 'study'" @click="toStudy"
                               label="Start" :disabled="!selectedDeck"/>
                </div>
            </div>

            <div v-if="!isLoading && decks.length > 0" class="deck-item-grid">
                <DeckFlashcard v-for="(deck, index) in decks" :key="deck.id" :model="deck"
                               :disabled="mode === 'study' && deck.terms.length === 0"
                               :active="selectedDeck?.id === deck.id"
                               @flip="toggleSelectDeck(index)"
                />
            </div>
            <div v-show="isLoading" class="app-loading">
                <img src="/img/wait.svg" alt="Loading"/>
            </div>
        </div>

        <template v-if="mode === 'build'">
            <div id="dm-build" v-if="step === 'build'">
                <Build :deck="selectedDeck"/>
            </div>
        </template>
        <template v-if="mode === 'study'">
            <div id="dm-study" v-if="step === 'study'">
                <Study :deck="selectedDeck" :terms="terms"/>
            </div>
        </template>
    </div>
</template>
