<script setup>
import {useStateStore} from "../stores/StateStore.js";
import {useDeckStore} from "../stores/DeckStore.js";
import {onMounted} from "vue";
import DeckItem from "../ui/DeckItem.vue";

const StateStore = useStateStore();
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
                  :class="{'slide-out': StateStore.data.transitioning}"
                  :unflip="StateStore.data.transitioning && deck.id === DeckStore.data.deck.id"
        />
    </div>
</template>
