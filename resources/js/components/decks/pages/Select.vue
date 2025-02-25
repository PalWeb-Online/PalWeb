<script setup>
import {onMounted, onUnmounted, ref} from "vue";
import {useStateStore} from "../stores/StateStore.js";
import {useDeckStore} from "../stores/DeckStore.js";
import {eventBus} from '../../../utils/eventBus.js';
import DeckItem from "../ui/DeckItem.vue";
import SearchGenie from "../../search/SearchGenie.vue";
import AppNotification from "../../AppNotification.vue";

const DeckStore = useDeckStore();
const StateStore = useStateStore();
const notification = ref(null);

const activeId = ref(null);

const toggleActive = (id, index = null) => {
    activeId.value = activeId.value === id ? null : id;
    DeckStore.toggleSelectDeck(index);
};

onMounted(async () => {
    DeckStore.data.stagedDeck = {
        id: '',
        name: '',
        description: '',
        terms: [],
        count: false,
        private: false,
    }
    DeckStore.data.originalDeck = JSON.parse(JSON.stringify(DeckStore.data.stagedDeck));

    activeId.value = DeckStore.data.stagedDeck.id ?? null;

    if (StateStore.data.context === 'builder') {
        await DeckStore.fetchCreatedDecks();
    } else if (StateStore.data.context === 'viewer') {
        await DeckStore.fetchPinnedDecks()
    }

    eventBus.on('pinnedModel', async (eventData) => {
        if (StateStore.data.context === 'viewer') {
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
    <SearchGenie v-if="StateStore.data.context === 'viewer'" :context="'viewer'"/>

    <div class="app-prompt-heading">
        <template v-if="StateStore.data.context === 'builder'">
            Select one of your created Decks, or proceed to create a new Deck.
        </template>
        <template v-else-if="StateStore.data.context === 'viewer'">
            Select one of your pinned Decks.
        </template>
    </div>
    <div class="deck-item-grid">
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

        <DeckItem v-for="(deck, index) in DeckStore.data.decks" :key="deck.id" :deck="deck"
                  :context="StateStore.data.context"
                  :isActive="activeId === deck.id"
                  @flip="toggleActive(deck.id, index)"
        />
    </div>

    <AppNotification ref="notification"/>
</template>
