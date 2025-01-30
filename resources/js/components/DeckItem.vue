<script setup>
import PinButton from "./PinButton.vue";
import PrivacyToggleButton from "./PrivacyToggleButton.vue";
import DeckActions from "./DeckActions.vue";
import {useDeck} from "../composables/Deck.js";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    id: Number,
    size: {type: String, default: 'm'},
});

const {data, description} = useDeck(props);
</script>

<template>
    <template v-if="! data.isLoading">
        <div :class="['deck-item-wrapper', size]">
            <DeckActions :model="data.deck"/>
            <div class="deck-item">
                <div class="item-data">
                    <div style="display: flex; gap: 0.8rem; align-items: center">
                        <div class="item-title">
                            {{ data.deck.name }}
                        </div>
                        <div class="deck-term-count">
                            ({{ data.deck.terms.length }})
                        </div>
                    </div>
                    <div v-if="size === 'l' && data.deck.description" class="item-description">
                        {{ description }}
                    </div>
                </div>

                <div class="deck-author">
                    <div class="deck-author-name">by {{ data.deck.author.name }}</div>
                    <div class="deck-author-avatar">
                        <img alt="Profile Picture" :src="`/img/avatars/${data.deck.author.avatar}`"/>
                    </div>
                </div>
                <PinButton modelType="deck" :model="data.deck"/>
                <PrivacyToggleButton modelType="deck" :model="data.deck"/>
            </div>
        </div>
    </template>
</template>
