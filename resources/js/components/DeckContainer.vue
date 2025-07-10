<script setup>
import {useDeck} from "../composables/Deck.js";
import DeckActions from "./DeckActions.vue";
import PinButton from "./PinButton.vue";
import TermItem from "./TermItem.vue";
import UserItem from "./UserItem.vue";
import AppTip from "./AppTip.vue";
import {route} from "ziggy-js";
import {useUserStore} from "../stores/UserStore.js";

const UserStore = useUserStore();

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
        <div class="window-container">
            <div class="window-header">
                <Link :href="route('decks.index')" class="material-symbols-rounded">home</Link>
                <div class="material-symbols-rounded" :class="deck.private ? 'private' : ''">
                    {{ deck.private ? 'lock' : 'public' }}
                </div>
                <div class="window-header-url">www.palweb.app/library/decks/{deck}</div>
                <Link :href="route('decks.random')" class="material-symbols-rounded">keyboard_double_arrow_right</Link>
            </div>
            <div class="window-section-head">
                <h1>deck</h1>
                <PinButton modelType="deck" :model="deck"/>
                <DeckActions :model="deck"/>
            </div>
            <AppTip v-if="deck.private">
                <p>This Deck is currently set to Private, so it will not be visible to others on the site; this page is
                    only visible to you. </p>
            </AppTip>

            <div class="window-content-head">
                <div class="window-content-head-title">{{ deck.name }}</div>
            </div>

            <UserItem :user="deck.author" size="m" comment>
                <template #comment>
                    <div class="user-comment-content">
                        <template v-if="deck.description">
                            {{ deck.description }}
                        </template>
                        <template v-else>
                            <i>Sadly, {{ deck.author.name }} hasn't told us anything about this Deck yet.</i>
                        </template>
                    </div>
                    <div class="user-comment-data">Created by {{ deck.author.name }} on {{ deck.created_at }}.</div>
                </template>
            </UserItem>

            <template v-if="deck.terms.length > 0">
                <div class="model-list index-list">
                    <TermItem v-for="term in deck.terms"
                              :key="term.id"
                              :model="term"
                              :glossId="term.deckPivot.gloss_id"
                    />
                </div>
                <div class="terms-count">{{ deck.terms.length }} Terms</div>
            </template>
            <template v-else>
                <AppTip>
                    <p>This Deck is still empty! If this Deck is yours, you can edit the Deck in the Deck Master by
                        selecting <b>Edit Deck</b> in the Context Actions menu. You may also click the folder icon
                        on any Term to view a list of your created Decks that you can add the Term to.
                    </p>
                </AppTip>
            </template>
        </div>
    </template>
</template>
