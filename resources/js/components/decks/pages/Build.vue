<script setup>
import {useStateStore} from "../stores/StateStore.js";
import {useDeckStore} from "../stores/DeckStore.js";
import SearchGenie from '../../search/SearchGenie.vue';
import TermItem from "../ui/TermItem.vue";
import draggable from 'vuedraggable';
import {onMounted, onUnmounted, ref} from "vue";
import AppButton from "../../AppButton.vue";
import AppNotification from "../../AppNotification.vue";

const StateStore = useStateStore();
const DeckStore = useDeckStore();
const notification = ref(null);

const insertTerm = (term) => {
    const termExists = DeckStore.data.terms.some(existingTerm => existingTerm.id === term.id);

    if (termExists) {
        notification.value.showNotification('This Term is already in the Deck!', 'error');

    } else {
        DeckStore.data.terms.push({
            id: term.id,
            term: term.term,
            category: term.category,
            translit: term.translit,
            glosses: term.glosses.map((gloss) => ({
                id: gloss.id,
                gloss: gloss.gloss,
            })),
            gloss_id: term.glosses[0].id,
            position: '',
        });
        updatePosition();
        notification.value.showNotification(`Added ${term.term} to the Deck!`);
    }
}

const removeTerm = (index) => {
    DeckStore.data.terms.splice(index, 1);
    updatePosition();
}

const updatePosition = () => {
    DeckStore.data.terms.forEach((term, index) => {
        term.position = index + 1;
    });
}

const saveDeck = async () => {
    const success = await DeckStore.saveDeck();

    if (success) {
        notification.value.showNotification('Your Deck has been saved!');
    } else {
        notification.value.showNotification(StateStore.data.errorMessage, 'error');
    }
};

onMounted(async () => {
    if (DeckStore.data.deck.id !== '') {
        await DeckStore.fetchTerms(DeckStore.data.deck.id);
    }
});

onUnmounted(() => {
    DeckStore.data.terms = [];
    DeckStore.data.deck = {
        id: '',
        name: '',
        description: '',
        terms: [],
        count: false,
        private: false,
    };
});
</script>

<template>
    <SearchGenie :context="'builder'" @emitTerm="insertTerm($event)"/>

    <div class="app-prompt-heading">Use the Search Genie to add Terms to the Deck!</div>

    <div class="deck-container">
        <div class="deck-container-head">
            <input class="deck-container-head-title" id="deck[name]" v-model="DeckStore.data.deck.name"
                   name="deck[name]" required type="text"/>

            <!--        <PrivacyToggleButton v-if="isAuthor"-->
            <!--                             :route="routes.privacyToggle"-->
            <!--                             :isPrivate="deck.isPrivate"-->
            <!--        />-->
            <!--            <label class="checkbox" for="deck[private]">-->
            <!--                <input type="checkbox" value=1 id="deck[private]" name="deck[private]"-->
            <!--                       v-model="DeckStore.data.deck.private">-->
            <!--                <span>Private</span>-->
            <!--            </label>-->
        </div>
        <div class="user-wrapper">
            <div class="user-avatar">
                <img :src="DeckStore.data.user.avatar" alt="Profile Picture"/>
            </div>
            <div class="user-comment">
                <div class="user-comment-head">
                    <div>{{ DeckStore.data.user.name }}</div>
                    <div>({{ DeckStore.data.user.username }})</div>
                </div>
                <div class="user-comment-body">
                    <textarea class="user-comment-body-content" id="deck[description]"
                              v-model="DeckStore.data.deck.description"
                              name="deck[description]"/>
                </div>
            </div>
        </div>

        <draggable :list="DeckStore.data.terms" @end="updatePosition()"
                   class="draggable">
            <template #item="{ element, index }">
                <div class="db-item">
                    <TermItem :term="element"/>
                    <img src="/img/trash.svg" alt="Delete" v-show="DeckStore.data.terms.length > 0"
                         @click="removeTerm(index)"/>
                </div>
            </template>
        </draggable>

        <div class="deck-term-count">{{ DeckStore.data.terms.length }} Terms</div>
    </div>

    <AppButton label="Save Deck"
               @click="saveDeck"
    />
    <AppNotification ref="notification"/>
</template>
