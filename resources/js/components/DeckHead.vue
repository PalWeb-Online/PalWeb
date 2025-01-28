<script setup>
import {useDeck} from "../composables/Deck.js";
import DeckActions from "./DeckActions.vue";
import PinButton from "./PinButton.vue";
import PrivacyToggleButton from "./PrivacyToggleButton.vue";

const props = defineProps({
    id: Number,
    size: {type: String, default: 'm'},
});

const { data } = useDeck(props);
</script>

<template>
    <template v-if="! data.isLoading">
        <div class="deck-container-head">
            <div class="deck-container-head-title">{{ data.deck.name }}</div>

            <PinButton modelType="deck" :model="data.deck"/>
            <PrivacyToggleButton modelType="deck" :model="data.deck"/>
            <DeckActions :model="data.deck"/>
        </div>
    </template>
</template>
