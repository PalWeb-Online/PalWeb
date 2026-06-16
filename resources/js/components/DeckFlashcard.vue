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
                    <div class="deck-flashcard-front-body"
                         :style="`font-style: ${ deck.description ? 'normal' : 'italic' }`">
                        {{ blurb ?? '' }}
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
                <div class="model-item-container deck-item-container">
                    <div class="model-item deck-item">
                        <PinButton modelType="deck" :model="deck"/>
                    </div>
                </div>

                <div v-if="deck.private" class="lock">
                    <div class="material-symbols-rounded">lock</div>
                </div>

                <div class="model-item-container deck-item-container">
                    <div class="model-item deck-item">
                        <img v-if="!deck.author.private" @click="router.get(route('users.show', deck.author.username))"
                             class="deck-author-avatar" alt="Avatar"
                             :src="deck.author.avatar_url"/>
                        <DeckActions :model="deck"/>
                    </div>
                </div>
            </div>
        </div>

        <AppTooltip ref="tooltip"/>
    </template>
</template>

<style scoped lang="scss">
.deck-flashcard-wrapper {
    display: grid;
    justify-items: center;
    position: relative;
    border-radius: 1.2rem;
    gap: 0.8rem;
}

.deck-flashcard-controls {
    display: flex;
    gap: 0.8rem;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    font-size: 0.6rem;
    padding-inline: 0.4rem;

    .deck-item-container {
        font-size: 1em;

        .model-item-content {
            padding-inline: 1em;
            cursor: default;

            .lock {
                font-size: 1.75em;
            }
        }
    }

    .pin-counter {
        visibility: hidden;
    }

    & > .lock {
        flex-grow: 1;
        display: flex;
        justify-content: flex-end;

        .material-symbols-rounded {
            border-radius: 50%;
            background: white;
            font-size: 2.5em;
            padding: 0.2em;
            color: var(--color-medium-secondary);
        }
    }
}

.deck-flashcard {
    width: 26rem;
    height: 32rem;
    border-radius: 1.2rem;
    perspective: 120rem;
    cursor: pointer;
    user-select: none;
    z-index: 1;

    &.disabled {
        filter: opacity(0.5);
        pointer-events: none;
    }

    .deck-flashcard-front, .deck-flashcard-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        transition: transform 0.3s cubic-bezier(.25, .46, .45, .94);
        font-family: var(--head-font);
        text-align: left;
        display: grid;
        align-items: start;
        align-content: start;
        color: var(--color-dark-primary);
        background: white;
        border-radius: 1.2rem;
        border: 0.2rem solid var(--color-dark-primary);
        box-shadow: -0.3rem 0.3rem 0 0 rgb(0 0 0 / 0.25);
        overflow: hidden;
    }

    .deck-flashcard-front {
        .deck-flashcard-front-head {
            display: grid;
            gap: 0.4rem;
            background: var(--color-pastel-light);
            padding: 1.2rem;

            .deck-author-name {
                font-family: var(--body-font);
                font-size: 1.4rem;
            }
        }

        .deck-flashcard-front-body {
            height: 100%;
            padding: 1.2rem;
            font-family: var(--body-font);
            font-size: 1.4rem;
        }
    }

    .deck-flashcard-back {
        display: grid;
        gap: 1.2rem;
        padding: 1.6rem;
        grid-template-rows: 1fr min-content;
        font-family: var(--mono-font);
        text-align: center;
        padding-block-end: 0.8rem;
        background: var(--color-dark-primary);
        transform: rotateY(180deg);
        overflow: hidden;

        .deck-flashcard-back-terms {
            flex-grow: 1;
            direction: rtl;
            display: grid;
            align-content: start;
            width: 100%;
            font-size: 1.6rem;
            row-gap: 0.4rem;
            color: white;
            grid-template-columns: repeat(2, 1fr);
        }

        .deck-flashcard-back-count {
            color: var(--color-accent-medium);
            font-size: 1.4rem;
            font-weight: 700;
            text-transform: uppercase;
        }
    }

    .deck-flashcard-action {
        color: white;
        font-size: 6.4rem;
        font-family: var(--display-font);
        background: var(--color-dark-primary);
        padding-block: 50%;
        text-align: center;
        text-transform: uppercase;
    }

    &.flipped {
        .deck-flashcard-front {
            transform: rotateY(-180deg);
        }

        .deck-flashcard-back {
            transform: rotateY(0);
        }
    }

    .model-item-title {
        font-weight: 700;
        font-size: 1.6rem;
    }
}
</style>
