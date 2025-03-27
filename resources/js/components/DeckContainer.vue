<script setup>
import {useDeck} from "../composables/Deck.js";
import DeckActions from "./DeckActions.vue";
import PinButton from "./PinButton.vue";
import TermItem from "./TermItem.vue";
import UserItem from "./UserItem.vue";

const props = defineProps({
    model: {
        type: Object,
        required: false,
        default: null,
    },
});

const {deck, isLoading} = useDeck(props);
</script>

<template>
    <template v-if="! isLoading">
        <div class="deck-container">
            <div class="deck-container-head">
                <div class="deck-container-head-title">{{ deck.name }}</div>

                <PinButton modelType="deck" :model="deck"/>
                <DeckActions :model="deck"/>
                <div class="action-buttons">
                    <img v-if="deck.private" src="/img/lock.svg" class="lock" alt="Privacy"/>
                </div>
            </div>

            <UserItem :user="deck.author" size="m" comment>
                <div class="user-comment-content">
                    <template v-if="deck.description">
                        {{ deck.description }}
                    </template>
                    <template v-else>
                        <i>Sadly, {{ deck.author.name }} hasn't told us anything about this Deck yet.</i>
                    </template>
                </div>
                <div class="user-comment-data">Created by {{ deck.author.name }} on {{ deck.created_at }}.</div>
            </UserItem>

            <template v-if="deck.terms.length > 0">
                <div class="terms-list">
                    <TermItem v-for="term in deck.terms" :model="term" :glossId="term.deckPivot.gloss_id"/>
                </div>
                <div class="deck-term-count">{{ deck.terms.length }} Terms</div>
            </template>
            <template v-else>
                <div class="tip">
                    <div class="material-symbols-rounded">info</div>
                    <div class="tip-content">
                        <p>This Deck is still empty! If this Deck is yours, you can edit the Deck in the Deck Master by
                            selecting <b>Edit Deck</b> in the Context Actions menu. You may also click the folder icon
                            on any Term to view a list of your created Decks that you can add the Term to.
                        </p>
                    </div>
                </div>
            </template>
        </div>
    </template>
</template>
