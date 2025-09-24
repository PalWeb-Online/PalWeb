<script setup>
import PinButton from "./PinButton.vue";
import DeckActions from "./Actions/DeckActions.vue";
import {useDeck} from "../composables/Deck.js";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {computed} from "vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
    size: {type: String, default: 'm'},
    target: {type: String, default: 'library'},
});

const formatter = new Intl.NumberFormat('en-US', {
    style: 'percent',
    maximumFractionDigits: 0,
});

const requestTarget = computed(() => {
    return props.target === 'library'
        ? route('decks.show', deck?.id)
        : route('scores.history', {
            scorable_type: 'deck',
            scorable_id: deck?.id
        });
})

const {deck, blurb, isLoading} = useDeck(props);
</script>

<template>
    <template v-if="! isLoading">
        <div class="deck-item-container">
            <div :class="['deck-item', size]">
                <PinButton modelType="deck" :model="deck"/>
                <div class="model-item-content" @click="router.get(requestTarget)">
                    <div class="model-item-title">
                        {{ deck.name }}
                        <span class="deck-item-terms-count">
                            {{ deck.terms_count }}
                        </span>
                    </div>
                    <div v-if="deck.private" class="material-symbols-rounded">
                        {{ deck.private ? 'lock' : 'public' }}
                    </div>
                    <div class="deck-author-name">
                        by {{ !deck.author.private ? deck.author.name : 'Anonymous' }}
                    </div>
                </div>
                <img v-if="!deck.author.private" @click="router.get(route('users.show', deck.author.username))"
                     class="deck-author-avatar" alt="Avatar"
                     :src="`/img/avatars/${deck.author.avatar}`"/>
                <DeckActions :model="deck"/>
            </div>

            <div v-if="size === 'l' && blurb" class="model-item-description"
                 :style="`font-style: ${ deck.description ? 'normal' : 'italic' }`">
                {{ blurb }}
            </div>
            <div v-if="deck.stats" class="model-item-stats">
                <span style="font-weight: 700">Times Quizzed</span>
                <span>{{ deck.stats.count }}</span>
                ·
                <span style="font-weight: 700">Latest Score</span>
                <span>{{ formatter.format(deck.stats.latest) }}</span>
                <span>({{ deck.stats.latest_date }})</span>
            </div>
        </div>
    </template>
</template>
