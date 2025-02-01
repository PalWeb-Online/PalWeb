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
    id: {type: Number, default: null},
    size: {type: String, default: 'm'},
    active: {type: Boolean, default: false},
    disabled: {type: Boolean, default: false},
});

const emit = defineEmits(['flip']);

const flipCard = (event) => {
    const card = event.target.closest('.deck-flashcard-wrapper').querySelector('.deck-flashcard');
    if (card) card.classList.toggle('flipped');

    emit('flip', data.deck.id);
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

const {data, description} = useDeck(props);
</script>

<template>
    <template v-if="! data.isLoading">
        <div :class="['deck-flashcard-wrapper', { active: active }]"
             @mousemove="disabled && tooltip.showTooltip('This Deck is empty.', $event);"
             @mouseleave="disabled && tooltip.hideTooltip()"
        >
            <div :class="['deck-flashcard', { flipped: active }, { disabled: disabled }]" ref="flashcard"
                 @click="flipCard">
                <div class="deck-flashcard-front">
                    <div class="deck-flashcard-front-head">
                        <div class="item-title">{{ data.deck.name }}</div>
                        <div class="deck-author" style="align-self: flex-end">
                            <div class="deck-author-name">by {{ data.deck.author.name }}</div>
                            <img class="deck-author-avatar" alt="Profile Picture"
                                 :src="`/img/avatars/${data.deck.author.avatar}`"/>
                            <!--                        <div class="deck-author-name">by Deleted User</div>-->
                        </div>
                    </div>
                    <div class="deck-flashcard-front-body">
                        <div v-if="data.deck.description" class="item-description">{{ description }}</div>
                    </div>
                </div>
                <div class="deck-flashcard-back">
                    <div class="deck-flashcard-back-head">{{ data.deck.terms.length }} terms</div>
                    <div class="deck-flashcard-back-body">
                        <div v-for="term in data.deck.terms.slice(0, 16)">{{ term.term }}</div>
                        <div v-if="data.deck.terms.length > 16" style="grid-column: span 2">...</div>
                    </div>
                </div>
            </div>

            <PinButton modelType="deck" :model="data.deck"/>
            <PrivacyToggleButton modelType="deck" :model="data.deck"/>
            <DeckActions :model="data.deck"/>
        </div>

        <AppTooltip ref="tooltip"/>
    </template>
</template>
