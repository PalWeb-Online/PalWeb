<script setup>
import {onMounted, ref} from 'vue';
import VanillaTilt from "vanilla-tilt";
import {useDeck} from "../composables/Deck.js";
import PinButton from "./PinButton.vue";
import DeckActions from "./Actions/DeckActions.vue";
import AppTooltip from "./AppTooltip.vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useUserStore} from "../stores/UserStore.js";

const UserStore = useUserStore();

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
                 @click="flipCard"
            >
                <div class="deck-flashcard-front">
                    <div class="deck-flashcard-front-head">
                        <div class="model-item-title">{{ deck.name }}</div>
                        <div class="deck-author-name">
                            by {{ !deck.author.private ? deck.author.name : 'Anonymous' }}
                        </div>
                    </div>
                    <div class="deck-flashcard-front-body">
                        <div v-if="blurb" class="item-description"
                             :style="`font-style: ${ deck.description ? 'normal' : 'italic' }`">
                            {{ blurb }}
                        </div>
                    </div>
                </div>
                <div class="deck-flashcard-back">
                    <div class="deck-flashcard-back-terms">
                        <div v-for="term in deck.terms.slice(0, 16)">{{ term.term }}</div>
                    </div>
                    <div class="deck-flashcard-back-count">{{ deck.terms.length }} terms</div>
                </div>
            </div>

            <div v-if="UserStore.isUser" class="deck-flashcard-controls">
                <div class="deck-item-container">
                    <div class="deck-item">
                        <PinButton modelType="deck" :model="deck"/>
                    </div>
                </div>

                <div v-if="deck.private" class="lock">
                    <div class="material-symbols-rounded">lock</div>
                </div>

                <div class="deck-item-container">
                    <div class="deck-item">
                        <img v-if="!deck.author.private" @click="router.get(route('users.show', deck.author.username))"
                             class="deck-author-avatar" alt="Avatar"
                             :src="`/img/avatars/${deck.author.avatar}`"/>
                        <DeckActions :model="deck"/>
                    </div>
                </div>
            </div>
        </div>

        <AppTooltip ref="tooltip"/>
    </template>
</template>
