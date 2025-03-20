<script setup>
import {useDeck} from "../composables/Deck.js";
import {route} from "ziggy-js";
import DeckActions from "./DeckActions.vue";
import PinButton from "./PinButton.vue";
import PrivacyToggleButton from "./PrivacyToggleButton.vue";
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
                    <PrivacyToggleButton modelType="deck" :model="deck"/>
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
                        <p>This Deck is still empty! If this Deck is yours, use the menu in the top-right corner of this
                            page to
                            <Link :href="route('decks.edit', deck.id)">Edit the Deck</Link>
                            , or hover over the Context
                            Actions
                            menu of a term & select the "Add to Deck" option to view a list of your Decks that you can
                            add
                            the
                            term
                            to.
                        </p>
                    </div>
                </div>
            </template>
        </div>
    </template>
</template>
