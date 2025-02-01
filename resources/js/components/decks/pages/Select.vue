<script setup>
import {onMounted, onUnmounted, ref} from "vue";
import {useStateStore} from "../stores/StateStore.js";
import {useDeckStore} from "../stores/DeckStore.js";
import {eventBus} from '../../../utils/eventBus.js';
import SearchGenie from "../../search/SearchGenie.vue";
import AppNotification from "../../AppNotification.vue";
import DeckFlashcard from "../../DeckFlashcard.vue";
import AppButton from "../../AppButton.vue";

const StateStore = useStateStore();
const DeckStore = useDeckStore();
const notification = ref(null);

const activeId = ref(null);

const toggleActive = (id, index = null) => {
    activeId.value = activeId.value === id ? null : id;
    DeckStore.toggleSelectDeck(index);
};

onMounted(async () => {
    DeckStore.data.stagedDeck = DeckStore.initializeDeck();
    DeckStore.data.originalDeck = null;

    activeId.value = DeckStore.data.stagedDeck.id ?? null;

    await DeckStore.setDecks();

    eventBus.on('pinnedModel', async (eventData) => {
        if (StateStore.data.mode === 'study') {
            if (eventData.model === 'deck') {
                await DeckStore.fetchPinnedDecks();
            }

            if (eventData.isPinned) {
                notification.value.showNotification(`Deck has been Pinned!`);
            }
        }
    });
});

onUnmounted(() => {
    eventBus.off('pinnedModel');
});
</script>

<template>
    <SearchGenie v-if="StateStore.data.mode === 'study'" :context="'viewer'"/>

    <!--    <div class="app-prompt-heading">-->
    <!--        <template v-if="StateStore.data.mode === 'build'">-->
    <!--            Select one of your created Decks, or proceed to create a new Deck.-->
    <!--        </template>-->
    <!--        <template v-else-if="StateStore.data.mode === 'study'">-->
    <!--            Select one of your pinned Decks.-->
    <!--        </template>-->
    <!--    </div>-->

    <div class="app-nav-interact">
        <div class="app-nav-interact-buttons">
            <AppButton v-if="StateStore.data.mode === 'build'" @click="StateStore.data.step = 'build'"
                       :label="DeckStore.data.stagedDeck.id ? 'Edit' : 'New'"/>
            <AppButton v-if="StateStore.data.mode === 'study'" @click="StateStore.data.step = 'study'"
                       label="Start" :disabled="!DeckStore.data.stagedDeck.id"/>
        </div>
    </div>

    <div v-if="!StateStore.data.isLoading" class="deck-item-grid">
        <!--        <DeckItem v-if="StateStore.data.context === 'builder'" :id="'0'"-->
        <!--                  :isActive="activeId === '0'"-->
        <!--                  @flip="toggleActive"-->
        <!--        >-->
        <!--            <template #front>-->
        <!--                <img src="/img/add.svg" alt="New"/>-->
        <!--            </template>-->
        <!--            <template #back>-->
        <!--                <div class="deck-flashcard-action">new</div>-->
        <!--            </template>-->
        <!--        </DeckItem>-->

        <DeckFlashcard v-for="(deck, index) in DeckStore.data.decks" :key="deck.id" :model="deck"
                       :disabled="StateStore.data.mode === 'study' && deck.terms.length === 0"
                       :active="activeId === deck.id"
                       @flip="toggleActive(deck.id, index)"
        />
    </div>
    <div v-show="StateStore.data.isLoading" class="app-loading">
        <img src="/img/wait.svg" alt="Loading"/>
    </div>

    <AppNotification ref="notification"/>
</template>
