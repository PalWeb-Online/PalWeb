<script setup>
import {useDeckStore} from "../stores/DeckStore.js";
import {onMounted} from "vue";
import DeckItem from "../ui/DeckItem.vue";

const DeckStore = useDeckStore();

onMounted(async () => {
    await DeckStore.fetchDecks();
});
</script>

<template>
    <div class="app-prompt-heading">
        Select one of your Pinned Decks
    </div>
    <div class="deck-item-grid">
        <DeckItem v-for="(deck, index) in DeckStore.data.pins"
                  :deck="deck" :active="deck.id === DeckStore.data.deck.id"
                  @click="DeckStore.selectDeck(index)"
        />
    </div>
</template>
