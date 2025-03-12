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
    size: {type: String, default: 'm'},
});

const {deck, blurb, isLoading} = useDeck(props);
</script>

<template>
    <template v-if="! isLoading">
        <div :class="['deck-item-wrapper', size]">
            <DeckActions :model="deck"/>
            <div class="deck-item">
                <div class="item-data">
                    <div style="display: flex; gap: 0.8rem; align-items: center">
                        <div class="item-title">
                            {{ deck.name }}
                        </div>
                        <div class="deck-term-count">
                            ({{ deck.terms_count }})
                        </div>
                    </div>
                    <div v-if="size === 'l' && blurb" class="item-description">
                        {{ blurb }}
                    </div>
                </div>

                <div class="deck-author">
                    <div class="deck-author-name">by {{ deck.author.name }}</div>
                    <div class="deck-author-avatar">
                        <img alt="Profile Picture" :src="`/img/avatars/${deck.author.avatar}`"/>
                    </div>
                </div>
                <PinButton modelType="deck" :model="deck"/>
                <PrivacyToggleButton modelType="deck" :model="deck"/>
            </div>
        </div>
    </template>
</template>
