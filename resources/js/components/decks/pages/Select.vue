<script setup>
import {onMounted, ref, watch} from "vue";
import {useUserStore} from "../../../stores/UserStore.js";
import {useStateStore} from "../stores/StateStore.js";
import {useDeckStore} from "../stores/DeckStore.js";
import SearchGenie from "../../search/SearchGenie.vue";
import DeckFlashcard from "../../DeckFlashcard.vue";
import AppButton from "../../AppButton.vue";

const UserStore = useUserStore();
const StateStore = useStateStore();
const DeckStore = useDeckStore();

const activeId = ref(null);

const toggleActive = (id, index = null) => {
    activeId.value = activeId.value === id ? null : id;
    DeckStore.toggleSelectDeck(index);
};

onMounted(() => {
    StateStore.data.isLoading = true;

    DeckStore.data.stagedDeck = DeckStore.initializeDeck();
    DeckStore.data.originalDeck = null;

    if (StateStore.data.mode === 'build') {
        DeckStore.data.decks = UserStore.user.decks;

    } else if (StateStore.data.mode === 'study') {
        DeckStore.data.decks = UserStore.user.pinned.decks;
    }

    activeId.value = DeckStore.data.stagedDeck.id ?? null;

    StateStore.data.isLoading = false;
});

watch(() => StateStore.data.mode, (newValue) => {
    StateStore.data.isLoading = true;

    if (newValue === 'build') {
        DeckStore.data.decks = UserStore.user.decks;

    } else if (newValue === 'study') {
        DeckStore.data.decks = UserStore.user.pinned.decks;
    }

    StateStore.data.isLoading = false;
});
</script>

<template>
    <SearchGenie v-if="StateStore.data.mode === 'study'" :context="'viewer'"/>

    <div class="app-nav-interact">
        <div class="app-nav-interact-buttons">
            <AppButton v-if="StateStore.data.mode === 'build'" @click="StateStore.toBuild"
                       :label="DeckStore.data.stagedDeck.id ? 'Edit' : 'New'"/>
            <AppButton v-if="StateStore.data.mode === 'study'" @click="StateStore.toStudy"
                       label="Start" :disabled="!DeckStore.data.stagedDeck.id"/>
        </div>
    </div>

    <div v-if="!StateStore.data.isLoading && DeckStore.data.decks.length > 0" class="deck-item-grid">
        <DeckFlashcard v-for="(deck, index) in DeckStore.data.decks" :key="deck.id" :model="deck"
                       :disabled="StateStore.data.mode === 'study' && deck.terms.length === 0"
                       :active="activeId === deck.id"
                       @flip="toggleActive(deck.id, index)"
        />
    </div>
    <div v-show="StateStore.data.isLoading" class="app-loading">
        <img src="/img/wait.svg" alt="Loading"/>
    </div>
</template>
