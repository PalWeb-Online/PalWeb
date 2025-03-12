<script setup>
import {onMounted, watch} from "vue";
import {useDeckMasterStore} from "../stores/DeckMasterStore.js";
import AppButton from "../../../../components/AppButton.vue";
import DeckFlashcard from "../../../../components/DeckFlashcard.vue";
import {cloneDeep} from "lodash";

const DeckMasterStore = useDeckMasterStore();

const toggleSelectDeck = (id, index = null) => {
    DeckMasterStore.data.activeId = DeckMasterStore.data.activeId === id ? null : id;

    if (index !== null) {
        if (DeckMasterStore.data.stagedDeck?.id === DeckMasterStore.data.decks[index].id) {
            DeckMasterStore.initializeDeck();

        } else {
            DeckMasterStore.data.stagedDeck = DeckMasterStore.data.decks[index];
            DeckMasterStore.data.originalDeck = cloneDeep(DeckMasterStore.data.stagedDeck);
        }

    } else {
        DeckMasterStore.initializeDeck();
    }
}

onMounted(() => {
    // todo: mode is always build
    DeckMasterStore.fetchDecks(DeckMasterStore.data.mode);

    if (DeckMasterStore.data.stagedDeck) {
        DeckMasterStore.data.activeId = DeckMasterStore.data.stagedDeck.id;

    } else {
        DeckMasterStore.data.activeId = null;
        DeckMasterStore.data.stagedDeck = DeckMasterStore.initializeDeck();
    }

});

watch(() => DeckMasterStore.data.mode, async (newMode) => {
    // todo: results in fetching twice
    await DeckMasterStore.fetchDecks(newMode);
});

</script>

<template>
    <div class="app-nav-interact">
        <div class="app-nav-interact-buttons">
            <AppButton v-if="DeckMasterStore.data.mode === 'build'" @click="DeckMasterStore.toBuild"
                       :label="DeckMasterStore.data.stagedDeck.id ? 'Edit' : 'New'"/>
            <AppButton v-if="DeckMasterStore.data.mode === 'study'" @click="DeckMasterStore.toStudy"
                       label="Start" :disabled="!DeckMasterStore.data.stagedDeck.id"/>
        </div>
    </div>

    <div v-if="!DeckMasterStore.data.isLoading && DeckMasterStore.data.decks.length > 0" class="deck-item-grid">
        <DeckFlashcard v-for="(deck, index) in DeckMasterStore.data.decks" :key="deck.id" :model="deck"
                       :disabled="DeckMasterStore.data.mode === 'study' && deck.terms.length === 0"
                       :active="DeckMasterStore.data.activeId === deck.id"
                       @flip="toggleSelectDeck(deck.id, index)"
        />
    </div>
    <div v-show="DeckMasterStore.data.isLoading" class="app-loading">
        <img src="/img/wait.svg" alt="Loading"/>
    </div>
</template>
