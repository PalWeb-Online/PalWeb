<script setup>
import {onMounted, ref} from 'vue';
import VanillaTilt from "vanilla-tilt";
import {useDeck} from "../composables/Deck.js";
import PinButton from "./PinButton.vue";
import PrivacyToggleButton from "./PrivacyToggleButton.vue";
import DeckActions from "./DeckActions.vue";
import AppTooltip from "./AppTooltip.vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    active: {type: Boolean, default: false},
    disabled: {type: Boolean, default: false},
});

const emit = defineEmits(['flip']);

const flipCard = (event) => {
    const card = event.target.closest('.deck-flashcard-wrapper').querySelector('.deck-flashcard');
    if (card) card.classList.toggle('flipped');

    emit('flip', deck.id);
};

const tooltip = ref(null);
const flashcard = ref(null);

onMounted(() => {
    VanillaTilt.init(flashcard.value, {
        max: 10,
        speed: 400,
        scale: 1,
    });
});

const {deck, blurb, isLoading} = useDeck(props);
</script>

<template>
    <template v-if="! isLoading">
        <div :class="['deck-flashcard-wrapper', { active: active }]"
             @mousemove="disabled && tooltip.showTooltip('This Deck is empty.', $event);"
             @mouseleave="disabled && tooltip.hideTooltip()"
        >
            <div :class="['deck-flashcard', { flipped: active }, { disabled: disabled }]" ref="flashcard"
                 @click="flipCard">
                <div class="deck-flashcard-front">
                    <div class="deck-flashcard-front-head">
                        <div class="item-title">{{ deck.name }}</div>
                        <div class="deck-author" style="align-self: flex-end">
                            <template v-if="!deck.author.private">
                                <div class="deck-author-name">by {{ deck.author.name }}</div>
                                <img class="deck-author-avatar" alt="Profile Picture"
                                     :src="`/img/avatars/${deck.author.avatar}`"/>
                            </template>
                            <template v-else>
                                <div class="deck-author-name">by Anonymous</div>
                            </template>
                        </div>
                    </div>
                    <div class="deck-flashcard-front-body">
                        <div v-if="blurb" class="item-description">{{ blurb }}</div>
                    </div>
                </div>
                <div class="deck-flashcard-back">
                    <div class="deck-flashcard-back-terms">
                        <div v-for="term in deck.terms.slice(0, 16)">{{ term.term }}</div>
                    </div>
                    <div class="deck-flashcard-back-count">{{ deck.terms.length }} terms</div>
                </div>
            </div>

            <PinButton modelType="deck" :model="deck"/>
            <DeckActions :model="deck"/>

            <div class="action-buttons">
                <PrivacyToggleButton modelType="deck" :model="deck"/>
            </div>
        </div>

        <AppTooltip ref="tooltip"/>
    </template>
</template>
