<script setup>
import PinButton from "./PinButton.vue";
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
                        <div class="item-count">
                            ({{ deck.terms_count }})
                        </div>
                    </div>
                    <div v-if="size === 'l' && blurb" class="item-description">
                        {{ blurb }}
                    </div>
                </div>

                <div class="deck-author">
                    <template v-if="!deck.author.private">
                        <div class="deck-author-name">by {{ deck.author.name }}</div>
                        <img class="deck-author-avatar" alt="Avatar"
                             :src="`/img/avatars/${deck.author.avatar}`"/>
                    </template>
                    <template v-else>
                        <div class="deck-author-name" style="padding-inline-end: 0.8rem">by Anonymous</div>
                    </template>
                </div>
                <PinButton modelType="deck" :model="deck" floating/>

                <div class="action-buttons">
                    <img v-if="deck.private" src="/img/lock.svg" class="lock" alt="Privacy"/>
                </div>
            </div>
        </div>
    </template>
</template>
